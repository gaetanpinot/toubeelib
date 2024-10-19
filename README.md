# Toubeelib

## Config
Fichier de config à créer (copier le fichier .dist existant en enlevant .dist):
- `./toubeelib.env`
- `./toubeelibdb.env`
- `./toubeelibauthdb.env`
- `./app/config/pdoConfig.ini`
- `./app/config/pdoConfigAuth.ini`
Les mots de passes doivent être consistant dans la db
## Todolist:
IUT Nancy-Charlemagne – BUT Informatique
S5 – DWM
Projet Développement Web Serveur avancé :
## Toubeelib, architecture générale (noté sur 10 points) :
- [x] API respectant les principes RESTful : désigation des ressources (URIs), opérations et méthodes
HTTP adéquates, status de retours corrects, données échangées au format JSON, incluant des
liens HATEOAS,
- [x] architecture basée sur les principes d’architecture Hexagonale et d’inversion de dépendances,
en particulier pour les bases de données,
- [x] utilisation d’un conteneur d’injection de dépendances,
- [x] traitement des erreurs et exceptions,
- [x] traitement des headers CORS,
- [x] authentification à l’aide de tokens JWT,
- [x] utilisation adéquate des mécanismes du framework Slim, notamment les middlewares,
- [x] validation et filtrage des données reçues au travers de l’API,
- [x] utilisation de bases de données distinctes pour les patients, pour les RDV, pour les praticiens et
ce qui s’y rattache, et pour l’authentification. Ces bases de données pourront éventuellement
être gérées dans des conteneurs Docker différents.
## Les fonctionnalités minimales attendues (notées sur 6 points) :
- [x] lister/rechercher des praticiens,
- [x] lister les disponibilités d’un praticien sur une période donnée (date de début, date de fin),
- [x] réserver un rendez-vous pour un praticien à une date/heure donnée,
- [x] annuler un rendez-vous, à la demande d’un patient ou d’un praticien,
- [ ] gérer le cycle de vie des rendez-vous (honoré, non honoré, payé),
- [ ] afficher le planning d’un praticien sur une période donnée (date de début, date de fin) en
précisant la spécialité concernée et le type de consultation (présentiel, téléconsultation),
- [ ] afficher les rendez-vous d’un patient,
- [ ] s’authentifier en tant que patient ou praticien.
## Les fonctionnalités additionnelles attendues (notées sur 4 points) :
- [ ] créer un praticien,
- [ ] s’inscrire en tant que patient
- [ ] gérer les indisponibilités d’un praticien : périodes ponctuelles sur lesquelles il ne peut accepter
de RDV,
- [ ] gérer les disponibilités d’un praticien : jours, horaires et durée des RDV pour chaque praticien,

