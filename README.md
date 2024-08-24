# Camagru


## techno ##
backend: 
  - php
base de donnée:  
  - postgres
frontend:  
  - html/css/javascript  
serveur:  
  - frankenPhp


## design

- [ ] design docker
- [ ] design backend ( architecture du code)
- [ ] design frontend ( maquette )
- [ ] design base de donnée 


## mise en place projet

- [ ] dockerfile server
- [ ] dockerfile backend
- [ ] dockerfile db

- [ ] docker-compose

- [ ] makefile

- [ ] fichiers de configs


## login

### frontend:
- [ ] avoir une page qui contient
    - [ ] champs nom d'utilisateur, mot de passe
    - [ ] bouton mot de passe oublié -> redirige sur page 'mot de passe oublié'
    - [ ] bouton register
    - [ ] bouton login -> si tout ok redirige sur la page home

### backend: 

- [ ] /api/login
    - [ ] valider le body de la requette
    - [ ] valider l'utilisateur ( nom d'utilisateur, mot de passe )
    - [ ] gestion d'erreur 
    - [ ] redirection
    - [ ] gestion injections sql
    - [ ] protection routes

--

## mot de passe oublié  

### frontend:
- [ ] champs mot de passe
- [ ] champs validation mot de pase
- [ ] boutton 'réinitialiser'

- [ ] si tout OK après click sur 'réinitialiser' redirigé sur la page login rediriger 

### backend:
- [ ] /api/send-mail-password-recovery
    - [ ] envoye mail de vérfication 
- [ ] /api/password-recovery 
    - [ ] change le mot de passe en base de donnée ( après validation du mail )

## register

### frontend:
- [ ] champs nom d'utilisateur
- [ ] champs email
- [ ] champs mdp
- [ ] champs validation mdp
- [ ] validation front des inputs ( vérification complexité du mdp + mdp et validation mdp sont identiques, email valide)
- [ ] bouton 'register' -> call backend -> si OK redirge sur Home

### backend:
- [ ] /api/register
  - [ ] validation du body
    - [ ] email unique
    - [ ] nom d'utilisateur unique
    - [ ] vérification complexité du mdp
       
### base de donnée
- [ ] si tout ok enregistrer nouvel utilisateur


## authentification 
midelware/guard


## Étapes pour Seed la Base de Données

1. Supprimer les Conteneurs et Volumes Existants (Optionnel)

```bash
docker-compose down -v
docker-compose up -d
docker exec -it postgres bash
psql -U ${POSTGRES_USER} -d ${POSTGRES_DB}
\i /docker-entrypoint-initdb.d/seed_user.sql
\i /docker-entrypoint-initdb.d/seed_posts.sql
```
