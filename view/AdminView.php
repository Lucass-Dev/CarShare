<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KPI - CarShare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-car"></i>
            <span>CarShare Admin</span>
        </div>
        <nav class="nav-menu">
            <a href="#" class="nav-item active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-route"></i>
                <span>Trajets</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Réservations</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Header -->
        <header class="dashboard-header">
            <div>
                <h1>Dashboard - Suivi des performances</h1>
                <p>Vue d'ensemble de la plateforme de covoiturage</p>
            </div>
            <div class="header-actions">
                <span class="date-info">
                    <i class="fas fa-calendar"></i>
                    Novembre 2024
                </span>
                <button class="btn-refresh">
                    <i class="fas fa-sync-alt"></i>
                    Actualiser
                </button>
            </div>
        </header>

        <!-- KPI Cards -->
        <section class="kpi-section">
            <div class="kpi-card">
                <div class="kpi-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="kpi-info">
                    <h3>245</h3>
                    <p>Utilisateurs inscrits</p>
                    <span class="kpi-trend positive">
                        <i class="fas fa-arrow-up"></i> +12% ce mois
                    </span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon green">
                    <i class="fas fa-route"></i>
                </div>
                <div class="kpi-info">
                    <h3>128</h3>
                    <p>Trajets publiés</p>
                    <span class="kpi-trend positive">
                        <i class="fas fa-arrow-up"></i> +8% ce mois
                    </span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon purple">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="kpi-info">
                    <h3>310</h3>
                    <p>Réservations effectuées</p>
                    <span class="kpi-trend positive">
                        <i class="fas fa-arrow-up"></i> +15% ce mois
                    </span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon orange">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="kpi-info">
                    <h3>68%</h3>
                    <p>Taux de remplissage</p>
                    <span class="kpi-trend positive">
                        <i class="fas fa-arrow-up"></i> +5% ce mois
                    </span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon yellow">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="kpi-info">
                    <h3>4 850 €</h3>
                    <p>Revenus simulés</p>
                    <span class="kpi-trend positive">
                        <i class="fas fa-arrow-up"></i> +20% ce mois
                    </span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="kpi-info">
                    <h3>12</h3>
                    <p>Trajets annulés</p>
                    <span class="kpi-trend negative">
                        <i class="fas fa-arrow-down"></i> -3% ce mois
                    </span>
                </div>
            </div>
        </section>

        <!-- Charts Section -->
        <section class="charts-section">
            <div class="chart-card">
                <h3>Évolution des inscriptions</h3>
                <div class="chart-container">
                    <div class="line-chart">
                        <div class="chart-bar" style="height: 40%;" data-value="45"></div>
                        <div class="chart-bar" style="height: 55%;" data-value="62"></div>
                        <div class="chart-bar" style="height: 48%;" data-value="54"></div>
                        <div class="chart-bar" style="height: 65%;" data-value="78"></div>
                        <div class="chart-bar" style="height: 75%;" data-value="89"></div>
                        <div class="chart-bar" style="height: 82%;" data-value="98"></div>
                    </div>
                    <div class="chart-labels">
                        <span>Juin</span>
                        <span>Juillet</span>
                        <span>Août</span>
                        <span>Sept</span>
                        <span>Oct</span>
                        <span>Nov</span>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <h3>Réservations par mois</h3>
                <div class="chart-container">
                    <div class="bar-chart">
                        <div class="bar" style="height: 45%;">
                            <span class="bar-value">32</span>
                        </div>
                        <div class="bar" style="height: 60%;">
                            <span class="bar-value">48</span>
                        </div>
                        <div class="bar" style="height: 52%;">
                            <span class="bar-value">42</span>
                        </div>
                        <div class="bar" style="height: 70%;">
                            <span class="bar-value">58</span>
                        </div>
                        <div class="bar" style="height: 85%;">
                            <span class="bar-value">72</span>
                        </div>
                        <div class="bar" style="height: 95%;">
                            <span class="bar-value">85</span>
                        </div>
                    </div>
                    <div class="chart-labels">
                        <span>Juin</span>
                        <span>Juillet</span>
                        <span>Août</span>
                        <span>Sept</span>
                        <span>Oct</span>
                        <span>Nov</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Analysis Section -->
        <section class="analysis-section">
            <div class="analysis-card">
                <h3><i class="fas fa-lightbulb"></i> Analyse rapide</h3>
                <p>
                    Le nombre de réservations est en progression ce mois-ci avec une augmentation de 15%. 
                    Le taux de remplissage dépasse 60%, ce qui indique une bonne utilisation des véhicules disponibles. 
                    Les inscriptions continuent de croître de manière régulière (+12%), démontrant l'attractivité de la plateforme.
                </p>
                <p>
                    Cependant, le nombre d'annulations reste à surveiller (12 annulations ce mois). 
                    Une analyse plus approfondie des causes permettrait d'améliorer la satisfaction utilisateur et de réduire ce taux.
                </p>
            </div>
        </section>

        <!-- Recent Activities -->
        <section class="activities-section">
            <h3>Activités récentes</h3>
            <div class="table-container">
                <table class="activities-table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Trajet</th>
                            <th>Date</th>
                            <th>Passagers</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Sophie Martin</span>
                                </div>
                            </td>
                            <td>Paris 6e → Versailles</td>
                            <td>21 Nov 2024</td>
                            <td>3/4</td>
                            <td><span class="status confirmed">Confirmé</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Thomas Dubois</span>
                                </div>
                            </td>
                            <td>Montparnasse → Orly</td>
                            <td>21 Nov 2024</td>
                            <td>2/4</td>
                            <td><span class="status pending">En attente</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Marie Lefebvre</span>
                                </div>
                            </td>
                            <td>Saint-Germain → La Défense</td>
                            <td>20 Nov 2024</td>
                            <td>4/4</td>
                            <td><span class="status confirmed">Confirmé</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Lucas Bernard</span>
                                </div>
                            </td>
                            <td>Paris 6e → Gare de Lyon</td>
                            <td>20 Nov 2024</td>
                            <td>1/3</td>
                            <td><span class="status pending">En attente</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Emma Petit</span>
                                </div>
                            </td>
                            <td>Châtelet → Roissy CDG</td>
                            <td>19 Nov 2024</td>
                            <td>0/4</td>
                            <td><span class="status cancelled">Annulé</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
<?php
class AdminView {
    // empty class
}
