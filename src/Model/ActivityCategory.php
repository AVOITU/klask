<?php

namespace Model;

class ActivityCategory
{
    public ?int $id_categorie = null;
    /** 'stand' | 'conference' | 'autres' */
    public string $type_categorie;
    public int $duree_max;
    public int $nbr_point;
    public ?int $nbr_max_eleve = null;

    public int $id_sphere;        // FK brute
    public ?Sphere $sphere = null; // lien objet

    /** @var Activite[] */
    public array $activites = [];
}