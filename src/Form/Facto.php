<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class Facto extends AbstractType {


    protected function facto($label, $placeholder, $options = []) {
        return array_merge(['label' => $label, 'attr' => ['placeholder' => $placeholder]], $options);
    }


}


?>