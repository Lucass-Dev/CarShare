# 🚗✨ Plateforme de Covoiturage — Guide d'Installation Cute Edition ✨🐰

Bienvenue dans ce magnifique projet de covoiturage 🌸
Ici, on partage des trajets… et de la douceur 💞

Ce guide explique comment :

* Installer & lancer le projet avec **MAMP / XAMPP**
* Installer la base MySQL via **terminal** ou **phpMyAdmin**
* Accéder au mock SQL 🗂️

---

## 🌈 Prérequis

* 🐘 PHP (fourni avec MAMP/XAMPP)
* 🐬 MySQL
* 🌍 phpMyAdmin
* 🧠 Ton cerveau génial
* ☕ Optionnel : un thé ou un café pour accompagner le voyage

---

## 🚀 Installation avec MAMP

1. Télécharger MAMP : [https://www.mamp.info/](https://www.mamp.info/)
2. Installer puis lancer **MAMP**
3. Cliquer sur **Start Servers**
4. Placer le projet dans :

**macOS**

```
/Applications/MAMP/htdocs/
```

**Windows**

```
C:\MAMP\htdocs\
```

5. Accéder à ton projet via :

```
http://localhost:8888/nom-du-projet
```

> 🎀 Apache + MySQL = 🌈

---

## 🔥 Installation avec XAMPP

1. Télécharger XAMPP : [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Installer puis lancer le **Control Panel**
3. Démarrer :

   * ✅ Apache
   * ✅ MySQL
4. Placer ton projet dans :

```
C:\xampp\htdocs\
```

5. Accéder au projet :

```
http://localhost/nom-du-projet
```

> 🐣 Ça démarre tout doucement…

---

## 🛠 Installer la Base de Données via Terminal

Place le fichier `mock_data.sql` sur ton ordinateur (ex: Bureau).

### macOS / Linux 🍎🐧

```bash
mysql -u root -p
CREATE DATABASE covoiturage;
USE covoiturage;
SOURCE /chemin/complet/mock_data.sql;
```

### Windows 💻

```bash
mysql -u root -p
CREATE DATABASE covoiturage;
USE covoiturage;
SOURCE C:\\chemin\\vers\\mock_data.sql;
```

> 🎉 La base est prête, comme un petit croissant sorti du four 🥐✨

---

## 🍼 Installer la BDD avec phpMyAdmin

1. Aller sur [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
2. Cliquer sur **Nouvelle base de données**
3. Nommer : `covoiturage`
4. Aller onglet **Importer**
5. Sélectionner `mock_data.sql`
6. Valider ✨

> 💖 Ta BDD est maintenant sur un plateau d'argent.

---

## 📌 Où trouver le mock SQL ?

> 🗂️ Le mock SQL complet est disponible sur **Trello** dans la carte :
> **`Structure et jeux de données mock (SQL)`**

---

## 🎯 Ready, set, ride 🛵💨

Tu peux maintenant :

* 🎬 Lancer ton app
* 🧪 Tester tes endpoints
* 🤝 Faire matcher des covoitureurs heureux

---

## 💬 Besoin d’aide ?

* Respire 🌬️
* Bois un chocolat chaud ☕
* Demande de l'aide si ça coince 💞

---

## ✨ Merci d’être toi ✨

Bon dev, petit génie du code 🧠💗
Let's make the world more cozy, one carpool at a time 🚗🌷
