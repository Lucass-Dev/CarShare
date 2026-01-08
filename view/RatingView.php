<style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f6f8fa;
            color: #24292f;
            line-height: 1.6;
        }

        main {
            min-height: calc(100vh - 200px);
        }

        .rating-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 24px;
            width: 100%;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 32px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #0969da;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 16px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .back-link:hover {
            background: #f6f8fa;
            border-color: #0969da;
        }

        .page-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 16px;
            color: #57606a;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 14px;
            border: 1px solid;
        }

        .alert-success {
            background: #ddf4ff;
            border-color: #54aeff;
            color: #0969da;
        }

        .alert-error {
            background: #ffebe9;
            border-color: #ff9b93;
            color: #cf222e;
        }

        .rating-card {
            background: white;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            background: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            margin-bottom: 32px;
        }

        .user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 20px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 4px;
        }

        .user-stats {
            display: flex;
            gap: 16px;
            font-size: 14px;
            color: #57606a;
        }

        .user-stat {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-group {
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 12px;
            display: block;
        }

        .star-rating {
            display: flex;
            gap: 12px;
            margin-bottom: 8px;
        }

        .star {
            font-size: 36px;
            color: #d0d7de;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }

        .star:hover,
        .star.active {
            color: #f59e0b;
            transform: scale(1.1);
        }

        .rating-label {
            font-size: 13px;
            color: #57606a;
            margin-top: 4px;
        }

        .textarea-wrapper {
            position: relative;
        }

        .form-textarea {
            width: 100%;
            min-height: 120px;
            padding: 12px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.2s;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #0969da;
            box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.1);
        }

        .char-counter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            font-size: 13px;
            color: #57606a;
        }

        .char-count {
            font-weight: 500;
        }

        .char-count.warning {
            color: #f59e0b;
        }

        .char-count.danger {
            color: #cf222e;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 24px;
            border-top: 1px solid #d0d7de;
        }

        .btn {
            padding: 10px 20px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn:hover {
            background: #f6f8fa;
        }

        .btn-primary {
            background: #0969da;
            border-color: #0969da;
            color: white;
        }

        .btn-primary:hover {
            background: #0550ae;
            border-color: #0550ae;
        }

        .btn-primary:disabled {
            background: #d0d7de;
            border-color: #d0d7de;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #0969da;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .back-link:hover {
            background: #f6f8fa;
            border-color: #0969da;
        }

        @media (max-width: 768px) {
            .container {
                padding: 24px 16px;
            }

            .top-bar {
                margin-bottom: 24px;
            }

            .page-title {
                font-size: 24px;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
            }

            .user-stats {
                flex-direction: column;
                gap: 8px;
            }

            .star-rating {
                justify-content: center;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

<div class="rating-container">
        <div class="top-bar">
            <a href="index.php?action=search" class="back-link">
                <span>←</span> Retour
            </a>
        </div>

        <div class="page-header">
            <h1 class="page-title">Donner un avis</h1>
            <p class="page-subtitle">Votre avis aide les autres utilisateurs à faire leur choix</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Merci pour votre avis !
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <?php 
            $errorMsg = 'Une erreur est survenue';
            if ($_GET['error'] === 'user_not_found') $errorMsg = 'Utilisateur non trouvé';
            elseif ($_GET['error'] === 'carpooling_not_found') $errorMsg = 'Trajet non trouvé';
            elseif ($_GET['error'] === 'save_failed') $errorMsg = 'Erreur lors de l\'enregistrement';
            elseif ($_GET['error'] === 'self_rating') $errorMsg = 'Vous ne pouvez pas vous évaluer vous-même';
            ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($errorMsg) ?>
            </div>
        <?php endif; ?>

        <div class="rating-card">
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($driver['name'], 0, 2)) ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($driver['name']) ?></div>
                    <div class="user-stats">
                        <div class="user-stat">
                            <span>Note moyenne:</span>
                            <strong><?= is_numeric($driver['avg']) ? number_format($driver['avg'], 1) . '/5' : 'N/A' ?></strong>
                        </div>
                        <span>•</span>
                        <div class="user-stat">
                            <span><?= htmlspecialchars((string)$driver['trips']) ?> trajets</span>
                        </div>
                        <span>•</span>
                        <div class="user-stat">
                            <span><?= htmlspecialchars((string)$driver['reviews']) ?> avis</span>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="/CarShare/index.php?action=rating_submit" id="ratingForm">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($driver['id']) ?>">
                <?php if (!empty($driver['carpooling_id'])): ?>
                <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($driver['carpooling_id']) ?>">
                <?php endif; ?>
                <input type="hidden" name="stars" id="starsInput" value="4">

                <div class="form-group">
                    <label class="form-label">Votre note</label>
                    <div class="star-rating" id="starRating">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star active" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                    <div class="rating-label" id="ratingLabel">Très bien</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="comment">Votre commentaire</label>
                    <div class="textarea-wrapper">
                        <textarea
                            id="comment"
                            name="comment"
                            class="form-textarea"
                            placeholder="Décrivez votre expérience avec <?= htmlspecialchars($driver['name']) ?>..."
                            maxlength="500"
                            required
                        ></textarea>
                        <div class="char-counter">
                            <span class="char-count" id="charCount">0 / 500</span>
                            <span id="charHint">Minimum 10 caractères</span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="index.php?action=search" class="btn">Annuler</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        Publier l'avis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const starRating = document.getElementById('starRating');
        const stars = starRating.querySelectorAll('.star');
        const starsInput = document.getElementById('starsInput');
        const ratingLabel = document.getElementById('ratingLabel');
        const commentTextarea = document.getElementById('comment');
        const charCount = document.getElementById('charCount');
        const charHint = document.getElementById('charHint');
        const submitBtn = document.getElementById('submitBtn');

        const ratingLabels = {
            1: 'Décevant',
            2: 'Passable',
            3: 'Correct',
            4: 'Très bien',
            5: 'Excellent'
        };

        let currentRating = 4;

        // Star rating interaction
        stars.forEach(star => {
            star.addEventListener('click', function() {
                currentRating = parseInt(this.dataset.rating);
                updateStars();
            });

            star.addEventListener('mouseenter', function() {
                const hoverRating = parseInt(this.dataset.rating);
                stars.forEach((s, index) => {
                    if (index < hoverRating) {
                        s.style.color = '#f59e0b';
                    } else {
                        s.style.color = '#d0d7de';
                    }
                });
            });
        });

        starRating.addEventListener('mouseleave', function() {
            updateStars();
        });

        function updateStars() {
            stars.forEach((star, index) => {
                if (index < currentRating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            starsInput.value = currentRating;
            ratingLabel.textContent = ratingLabels[currentRating];
        }

        // Character counter and validation
        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const remaining = 500 - length;

            charCount.textContent = `${length} / 500`;

            if (remaining < 50) {
                charCount.classList.add('warning');
                charCount.classList.remove('danger');
            }
            if (remaining < 20) {
                charCount.classList.add('danger');
                charCount.classList.remove('warning');
            }
            if (remaining >= 50) {
                charCount.classList.remove('warning', 'danger');
            }

            // Update hint
            if (length < 10) {
                charHint.textContent = `Encore ${10 - length} caractères minimum`;
                charHint.style.color = '#57606a';
                submitBtn.disabled = true;
            } else {
                charHint.textContent = 'Prêt à publier';
                charHint.style.color = '#1a7f37';
                submitBtn.disabled = false;
            }
        });

        // Auto-resize textarea
        commentTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.max(this.scrollHeight, 120) + 'px';
        });

        // Form validation
        document.getElementById('ratingForm').addEventListener('submit', function(e) {
            const comment = commentTextarea.value.trim();
            if (comment.length < 10) {
                e.preventDefault();
                alert('Votre commentaire doit contenir au moins 10 caractères.');
                commentTextarea.focus();
            }
        });
    </script>
