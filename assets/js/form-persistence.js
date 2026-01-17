(function () {
    'use strict';

    const DATASET_KEYS = ['selectedFromList', 'cityId', 'cityName', 'postalCode'];
    const STORAGE_PREFIX = 'carshare:form-state:';

    const hasStorageSupport = (() => {
        try {
            const testKey = '__carshare_form_test__';
            window.localStorage.setItem(testKey, '1');
            window.localStorage.removeItem(testKey);
            return true;
        } catch (error) {
            console.warn('Form persistence disabled (storage unavailable):', error);
            return false;
        }
    })();

    const cssEscape = (value) => {
        if (typeof CSS !== 'undefined' && typeof CSS.escape === 'function') {
            return CSS.escape(value);
        }
        return value.replace(/([\0-\x1F\x7F-\x9F\s#.;,:+*?^$&(){}|\[\]\\/<>"'=])/g, '\\$1');
    };

    class FormStateManager {
        constructor(form) {
            this.form = form;
            const attrKey = form.getAttribute('data-persist-key');
            const fallbackKey = form.id || form.getAttribute('name') || window.location.pathname;
            this.storageKey = `${STORAGE_PREFIX}${attrKey || fallbackKey}`;
            this.debounceDelay = parseInt(form.getAttribute('data-persist-debounce') || '300', 10);
            this.saveTimer = null;

            this.restore();
            this.bindEvents();
        }

        bindEvents() {
            const handler = () => this.queueSave();
            this.form.addEventListener('input', handler, true);
            this.form.addEventListener('change', handler, true);
            this.form.addEventListener('submit', () => this.clear());
            this.form.addEventListener('reset', () => this.clear());
        }

        queueSave() {
            clearTimeout(this.saveTimer);
            this.saveTimer = window.setTimeout(() => this.save(), this.debounceDelay);
        }

        getPersistableFields() {
            return Array.from(this.form.elements).filter((field) => this.shouldPersistField(field));
        }

        shouldPersistField(field) {
            if (!field || field.disabled) return false;
            const name = field.getAttribute('name');
            const type = (field.type || '').toLowerCase();
            if (!name && !field.id) return false;
            if (field.matches('[data-no-persist]')) return false;
            if (['password', 'file'].includes(type)) return false;
            if (field.tagName === 'BUTTON') return false;
            return true;
        }

        save() {
            try {
                const payload = {
                    version: 1,
                    fields: []
                };

                this.getPersistableFields().forEach((field) => {
                    const fieldType = (field.type || '').toLowerCase();
                    const entry = {
                        id: field.id || null,
                        name: field.name || null,
                        type: fieldType,
                        tag: field.tagName.toLowerCase()
                    };

                    if (field.tagName === 'SELECT' && field.multiple) {
                        entry.type = 'select-multiple';
                        entry.values = Array.from(field.selectedOptions).map((option) => option.value);
                    } else if (fieldType === 'checkbox' || fieldType === 'radio') {
                        entry.value = field.value;
                        entry.checked = field.checked;
                    } else {
                        entry.value = field.value;
                    }

                    const datasetSnapshot = {};
                    DATASET_KEYS.forEach((key) => {
                        if (field.dataset && field.dataset[key]) {
                            datasetSnapshot[key] = field.dataset[key];
                        }
                    });
                    if (Object.keys(datasetSnapshot).length > 0) {
                        entry.dataset = datasetSnapshot;
                    }

                    payload.fields.push(entry);
                });

                window.localStorage.setItem(this.storageKey, JSON.stringify(payload));
            } catch (error) {
                console.warn('Cannot persist form data:', error);
            }
        }

        restore() {
            let stored = null;
            try {
                stored = window.localStorage.getItem(this.storageKey);
                if (!stored) {
                    return;
                }
            } catch (error) {
                console.warn('Cannot access stored form data:', error);
                return;
            }

            try {
                const payload = JSON.parse(stored);
                if (!payload || !Array.isArray(payload.fields)) return;

                payload.fields.forEach((entry) => {
                    const targets = this.findMatchingFields(entry);
                    targets.forEach((field) => {
                        this.applyEntry(field, entry);
                    });
                });
            } catch (error) {
                console.warn('Cannot parse stored form data:', error);
            }
        }

        findMatchingFields(entry) {
            const matches = [];

            if (entry.id) {
                const byId = this.form.querySelector(`#${cssEscape(entry.id)}`);
                if (byId) {
                    matches.push(byId);
                    return matches;
                }
            }

            if (entry.name) {
                const selector = `[name="${cssEscape(entry.name)}"]`;
                this.form.querySelectorAll(selector).forEach((field) => matches.push(field));
            }

            return matches;
        }

        applyEntry(field, entry) {
            if (!this.shouldPersistField(field)) return;

            let changed = false;
            const fieldType = (field.type || '').toLowerCase();

            if (entry.type === 'select-multiple' && field.tagName === 'SELECT' && field.multiple) {
                const values = Array.isArray(entry.values) ? new Set(entry.values) : new Set();
                const previous = Array.from(field.selectedOptions).map((option) => option.value);
                const hasDifference = previous.length !== values.size || previous.some((value) => !values.has(value));
                if (hasDifference) {
                    Array.from(field.options).forEach((option) => {
                        option.selected = values.has(option.value);
                    });
                    changed = true;
                }
            } else if (fieldType === 'checkbox' || fieldType === 'radio') {
                if (entry.value === field.value && typeof entry.checked === 'boolean' && field.checked !== entry.checked) {
                    field.checked = entry.checked;
                    changed = true;
                }
            } else if (typeof entry.value === 'string' && field.value !== entry.value) {
                field.value = entry.value;
                changed = true;
            }

            if (entry.dataset && field.dataset) {
                DATASET_KEYS.forEach((key) => {
                    if (entry.dataset[key]) {
                        if (field.dataset[key] !== entry.dataset[key]) {
                            field.dataset[key] = entry.dataset[key];
                            changed = true;
                        }
                    }
                });
            }

            if (changed) {
                this.emitRestoreEvents(field, fieldType);
            }
        }

        emitRestoreEvents(field, fieldType) {
            const events = fieldType === 'radio' || fieldType === 'checkbox' ? ['change'] : ['input', 'change'];
            events.forEach((type) => {
                const event = new Event(type, { bubbles: true });
                Object.defineProperty(event, 'isTrusted', { value: false, writable: false });
                field.dispatchEvent(event);
            });
        }

        clear() {
            window.localStorage.removeItem(this.storageKey);
        }
    }

    if (!hasStorageSupport) {
        return;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-persist="true"]').forEach((form) => {
            new FormStateManager(form);
        });
    });
})();
