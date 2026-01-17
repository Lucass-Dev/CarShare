    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?= urlencode(PayPalConfig::getClientId()) ?>&currency=EUR&intent=capture"></script>
    <script>
        // Form elements
        const submitButton = document.getElementById('submit-button');
        const cardholderNameInput = document.getElementById('cardholder-name');
        const termsCheckbox = document.getElementById('accept_terms');
        const cardErrors = document.getElementById('card-errors');

        // Simple client-side validation helper
        function validateForm() {
            cardErrors.textContent = '';
            const name = cardholderNameInput.value.trim();
            if (!name || name.length < 2) {
                cardErrors.textContent = 'Veuillez entrer le nom complet du titulaire.';
                return false;
            }
            if (!termsCheckbox.checked) {
                cardErrors.textContent = 'Vous devez accepter les conditions pour continuer.';
                return false;
            }
            return true;
        }

        // Render PayPal button
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'blue',
                shape: 'rect',
                label: 'paypal'
            },
            onClick: function(data, actions) {
                // Validate form before opening PayPal window
                if (!validateForm()) {
                    return actions.reject();
                }
                return actions.resolve();
            },
            createOrder: function(data, actions) {
                }).render('#paypal-button-container');
            </script>
                </div>
            </div>

            <?php require_once __DIR__ . '/components/footer.php'; ?>
                        
                        console.error('Fetch error:', fetchError);
                    }
                }
            } catch (stripeError) {
                overlay.classList.remove('active');
                submitButton.disabled = false;
                submitButton.innerHTML = '<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><rect x=\"3\" y=\"11\" width=\"18\" height=\"11\" rx=\"2\" ry=\"2\"/><path d=\"M7 11V7a5 5 0 0110 0v4\"/></svg> Vérifier ma carte et confirmer';
                
                const errorMsg = '❌ Erreur inattendue. Veuillez recharger la page et réessayer.';
                
                if (typeof showNotification === 'function') {
                    showNotification('error', errorMsg);
                } else {
                    alert(errorMsg);
                }
                
                console.error('Stripe error:', stripeError);
            }
        });
        
        // Validation du nom en temps réel
        cardholderNameInput.addEventListener('input', function(e) {
            const value = e.target.value;
            // Autoriser seulement lettres, espaces, apostrophes et tirets
            e.target.value = value.replace(/[^a-zA-ZÀ-ÿ\s'-]/g, '');
        });
    </script>

    <?php require_once __DIR__ . '/components/footer.php'; ?>
</body>
</html>
