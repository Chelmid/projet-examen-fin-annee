<?php

namespace App\Service;


use App\Repository\PersonnalisationRepository;

class PersonnalisationService
{
    protected $personnalisation;

    public function __construct(PersonnalisationRepository $personnalisationRepository)
    {
        $this->personnalisation = $personnalisationRepository;
    }

    public function getPersonnalisation($id)
    {
        $personnalisation = [];

        foreach ($this->personnalisation->findById($id) as $value){
            $personnalisation = $value;
        }
        return $personnalisation;
    }
}
