<?php

// Initialisation des variables
$prev = $this->iCurrentPage - 1; // num�ro de la page pr�c�dente
$next = $this->iCurrentPage + 1; // num�ro de la page suivante
$penultimate = $this->iNbPages - 1; // num�ro de l'avant-derni�re page
$pagination = ''; // variable retour de la fonction : vide tant qu'il n'y a pas au moins 2 pages

if ($this->iNbPages > 1) {
    // Remplissage de la cha�ne de caract�res � retourner
    $pagination .= "<ul class=\"dc_pagination dc_paginationA dc_paginationA06\">\n";

    /* =================================
     *  Affichage du bouton [pr�c�dent]
     * ================================= */
    if ($this->iCurrentPage == 2) {
        // la page courante est la 2, le bouton renvoie donc sur la page 1, remarquez qu'il est inutile de mettre $url1
        $pagination .= "<li><a href=\"{$urlStart}1{$urlEnd}\">Précédent</a></li>";
    } elseif ($this->iCurrentPage > 2) {
        // la page courante est sup�rieure � 2, le bouton renvoie sur la page dont le num�ro est imm�diatement inf�rieur
        $pagination .= "<li><a href=\"{$urlStart}{$prev}{$urlEnd}\">Précédent</a></li>";
    } else {
        // dans tous les autres, cas la page est 1 : d�sactivation du bouton [pr�c�dent]
        $pagination .= '<li><span class="inactive">Précédent</span></li>';
    }

    /**
     * D�but affichage des pages, l'exemple reprend le cas de 3 num�ros de pages adjacents (par d�faut) de chaque c�t� du num�ro courant
     * - CAS 1 : il y a au plus 12 pages, insuffisant pour faire une troncature
     * - CAS 2 : il y a au moins 13 pages, on effectue la troncature pour afficher 11 num�ros de pages au this->iNbPages
     */
    /* ===============================================
     *  CAS 1 : au plus 12 pages -> pas de troncature
     * =============================================== */
    if ($this->iNbPages < 7 + ($adj * 2)) {
        // Ajout de la page 1 : on la traite en dehors de la boucle pour n'avoir que index.php au lieu de index.php?p=1 et ainsi �viter le duplicate content
        $pagination .= ($this->iCurrentPage == 1) ? '<li><span class="active">1</span></li>' : "<li><a href=\"{$urlStart}1{$urlEnd}\">1</a></li>"; // Op�rateur ternaire : (condition) ? 'valeur si vrai' : 'valeur si fausse'
        // Pour les pages restantes on utilise it�re
        for ($i = 2; $i <= $this->iNbPages; $i++) {
            if ($i == $this->iCurrentPage) {
                // Le num�ro de la page courante est mis en �vidence (cf. CSS)
                $pagination .= "<li><span class=\"active\">{$i}</span></li>";
            } else {
                // Les autres sont affich�es normalement
                $pagination .= "<li><a href=\"{$urlStart}{$i}{$urlEnd}\">{$i}</a></li>";
            }
        }
    }
    /* =========================================
     *  CAS 2 : au moins 13 pages -> troncature
     * ========================================= */ else {
        /**
         * Troncature 1 : on se situe dans la partie proche des premi�res pages, on tronque donc la fin de la pagination.
         * l'affichage sera de neuf num�ros de pages � gauche ... deux � droite
         * 1 2 3 4 5 6 7 8 9 � 16 17
         */
        if ($this->iCurrentPage < 2 + ($adj * 2)) {
            // Affichage du num�ro de page 1
            $pagination .= ($this->iCurrentPage == 1) ? "<li><span class=\"active\">1</span></li>" : "<li><a href=\"{$urlStart}1{$urlEnd}\">1</a></li>";

            // puis des huit autres suivants
            for ($i = 2; $i < 4 + ($adj * 2); $i++) {
                if ($i == $this->iCurrentPage) {
                    $pagination .= "<li><span class=\"active\">{$i}</span></li>";
                } else {
                    $pagination .= "<li><a href=\"{$urlStart}{$i}{$urlEnd}\">{$i}</a></li>";
                }
            }

            // ... pour marquer la troncature
            $pagination .= '<li>&hellip;</li>';

            // et enfin les deux derniers num�ros
            $pagination .= "<li><a href=\"{$urlStart}{$penultimate}{$urlEnd}\">{$penultimate}</a></li>";
            $pagination .= "<li><a href=\"{$urlStart}{$this->iNbPages}{$urlEnd}\">{$this->iNbPages}</a></li>";
        }
        /**
         * Troncature 2 : on se situe dans la partie centrale de notre pagination, on tronque donc le d�but et la fin de la pagination.
         * l'affichage sera deux num�ros de pages � gauche ... sept au centre ... deux � droite
         * 1 2 � 5 6 7 8 9 10 11 � 16 17
         */ elseif ((($adj * 2) + 1 < $this->iCurrentPage) && ($this->iCurrentPage < $this->iNbPages - ($adj * 2))) {
            // Affichage des num�ros 1 et 2
            $pagination .= "<li><a href=\"{$urlStart}1{$urlEnd}\">1</a></li>";
            $pagination .= "<li><a href=\"{$urlStart}2{$urlEnd}\">2</a></li>";
            $pagination .= '<li>&hellip;</li>';

            // les pages du milieu : les trois pr�c�dant la page courante, la page courante, puis les trois lui succ�dant
            for ($i = $this->iCurrentPage - $adj; $i <= $this->iCurrentPage + $adj; $i++) {
                if ($i == $this->iCurrentPage) {
                    $pagination .= "<li><span class=\"active\">{$i}</span></li>";
                } else {
                    $pagination .= "<li><a href=\"{$urlStart}{$i}{$urlEnd}\">{$i}</a></li>";
                }
            }

            $pagination .= '<li>&hellip;</li>';

            // et les deux derniers num�ros
            $pagination .= "<li><a href=\"{$urlStart}{$penultimate}{$urlEnd}\">{$penultimate}</a></li>";
            $pagination .= "<li><a href=\"{$urlStart}{$this->iNbPages}{$urlEnd}\">{$this->iNbPages}</a></li>";
        }
        /**
         * Troncature 3 : on se situe dans la partie de droite, on tronque donc le d�but de la pagination.
         * l'affichage sera deux num�ros de pages � gauche ... neuf � droite
         * 1 2 � 9 10 11 12 13 14 15 16 17
         */ else {
            // Affichage des num�ros 1 et 2
            $pagination .= "<li><a href=\"{$urlStart}1{$urlEnd}\">1</a></li>";
            $pagination .= "<li><a href=\"{$urlStart}2{$urlEnd}\">2</a></li>";
            $pagination .= '<li>&hellip;</li>';

            // puis des neuf derniers num�ros
            for ($i = $this->iNbPages - (2 + ($adj * 2)); $i <= $this->iNbPages; $i++) {
                if ($i == $this->iCurrentPage) {
                    $pagination .= "<li><span class=\"active\">{$i}</span></li>";
                } else {
                    $pagination .= "<li><a href=\"{$urlStart}{$i}{$urlEnd}\">{$i}</a></li>";
                }
            }
        }
    }

    /* ===============================
     *  Affichage du bouton [suivant]
     * =============================== */
    if ($this->iCurrentPage == $this->iNbPages)
        $pagination .= "<li><span class=\"inactive\">Suivant</span></li>";
    else
        $pagination .= "<li><a href=\"{$urlStart}{$next}{$urlEnd}\">Suivant</a></li>";

    // Fermeture de la <ul> d'affichage
    $pagination .= "</ul>";
}

echo $pagination;