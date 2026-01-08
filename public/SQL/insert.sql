DELETE FROM classes;
INSERT INTO classes (school, name_class) VALUES 

('Collège La Tour d\'Auvergne - Quimper', '3ème'),
('Collège Max Jacob - Quimper', '3ème'),
('Collège Brizeux - Quimper', '3ème'),
('Collège Saint-Yves - Quimper', '3ème'),
('Collège Saint-Jean Baptiste - Quimper', '3ème'),
('Collège Sainte-Thérèse - Quimper', '3ème'),
('Collège Diwan - Quimper', '3ème'),
('Collège Laennec - Pont-l\'Abbé', '3ème'),
('Collège de Kervihan - Fouesnant', '3ème'),
('Collège Saint-Joseph - Fouesnant', '3ème'),
('Collège des Sables Blancs - Concarneau', '3ème'),
('Collège Saint-Blaise - Douarnenez', '3ème'),

('Lycée Brizeux - Quimper', 'Seconde'),
('Lycée Chaptal - Quimper', 'Seconde'),
('Lycée de Cornouaille - Quimper', 'Seconde'),
('Lycée Yves Thépot - Quimper', 'Seconde'),
('Lycée Le Likès - Quimper', 'Seconde'),
('Lycée Sainte-Thérèse - Quimper', 'Seconde'),
('Lycée Le Paraclet - Quimper', 'Seconde'),
('Lycée Laennec - Pont-l\'Abbé', 'Seconde'),
('Lycée Saint-Gabriel - Pont-l\'Abbé', 'Seconde'),
('Lycée Pierre Guéguin - Concarneau', 'Seconde'),
('Lycée Saint-Joseph - Concarneau', 'Seconde'),
('Lycée Jean-Marie Le Bris - Douarnenez', 'Seconde'),


('Lycée Brizeux - Quimper', 'Première'),
('Lycée Chaptal - Quimper', 'Première'),
('Lycée de Cornouaille - Quimper', 'Première'),
('Lycée Yves Thépot - Quimper', 'Première'),
('Lycée Le Likès - Quimper', 'Première'),
('Lycée Sainte-Thérèse - Quimper', 'Première'),
('Lycée Le Paraclet - Quimper', 'Première'),
('Lycée Laennec - Pont-l\'Abbé', 'Première'),
('Lycée Saint-Gabriel - Pont-l\'Abbé', 'Première'),
('Lycée Pierre Guéguin - Concarneau', 'Première'),
('Lycée Saint-Joseph - Concarneau', 'Première'),
('Lycée Jean-Marie Le Bris - Douarnenez', 'Première');

INSERT INTO authorities (role_user, authority_user)
VALUES
    ('ADMIN', 'ROLE_ADMIN'),
    ('ACCOMPANYING', 'ROLE_ACCOMPANYING'),
    ('STUDENT', 'ROLE_STUDENT');