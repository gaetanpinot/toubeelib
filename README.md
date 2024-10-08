# Toubeelib

## Todolist:
- [ ] API respectant les principes RESTful : désigation des ressources (URIs), opérations et méthodes
HTTP adéquates, status de retours corrects, données échangées au format JSON, incluant des
liens HATEOAS,
- [x] architecture basée sur les principes d’architecture Hexagonale et d’inversion de dépendances,
en particulier pour les bases de données,
- [x] utilisation d’un conteneur d’injection de dépendances,
- [ ] traitement des erreurs et exceptions,
- [ ] traitement des headers CORS,
- [ ] authentification à l’aide de tokens JWT,
- [ ] utilisation adéquate des mécanismes du framework Slim, notamment les middlewares,
- [ ] validation et filtrage des données reçues au travers de l’API,
- [ ] utilisation de bases de données distinctes pour les patients, pour les RDV, pour les praticiens et
ce qui s’y rattache, et pour l’authentification. Ces bases de données pourront éventuellement
être gérées dans des conteneurs Docker différents.

- [ ] créer un patient,
- [ ] lister/rechercher des praticiens par spécialité et/ou lieu d’exercice (ville),
- [ ] lister les disponibilités d’un praticien sur une période donnée (date de début, date de fin),
- [ ] réserver un rendez-vous pour un praticien et une spécialité à une date/heure donnée,
- [ ] annuler un rendez-vous, à la demande d’un patient ou d’un praticien,
- [ ] gérer le cycle de vie des rendez-vous (honoré, non honoré, payé),
- [ ] afficher le planning d’un praticien sur une période donnée (date de début, date de fin) en
précisant la spécialité concernée et le type de consultation (présentiel, téléconsultation),
- [ ] afficher les rendez-vous d’un patient,
- [ ] s’authentifier en tant que patient ou praticien.

- [ ] créer un praticien,
- [ ] gérer les personnels médicaux associés à un un ou plusieurs praticiens. Pour les praticiens
auxquels ils sont associés, ces utilisateurs peuvent:
- [ ] afficher le planning d’un praticien, pour une période donnée,
- [ ] créer un RDV pour ce praticien,
- [ ] annuler un RDV pour ce praticien,
- [ ] gérer le cycle de vie des RDV pour ce praticien.
- [ ] s’authentifier en tant praticien, patient ou personnel médical.

- [ ] ajouter un document au dossier d’un patient,
- [ ] consulter le dossier d’un patient (réservé au patient ou aux praticiens),
- [ ] gérer les indisponibilités d’un praticien : périodes ponctuelles sur lesquelles il ne peut accepter
de RDV,
- [ ] gérer les disponibilités d’un praticien : jours, horaires et durée des RDV pour chaque praticien,
- [ ] gérer les spécialités des praticiens
