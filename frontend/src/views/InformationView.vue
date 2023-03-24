<template>
  <p>Hello</p>
</template>

<script lang="ts">
export default {
  name: "InformationView",

  /*
        Objectif : Pouvoir mettre à jour des informations sur la colocation

        -> mettre à jour les infos de la colocation
          -> changer les attributs de la coloc
          -> mettre à jour les charges (ajouter/supprimer/modifier) 
            -> supprimer un charge :  users/:user/relationships/:relationship (OK)
            -> modifier une charge : charges/:charges (OK)
            -> ajouter une charge : colocation/:colocation/charges (OK)

        -> supprimer un colocataire et mettre à jour les charges en conséquence
          -> /colocations/:colocation/relationships/:relationship (OK) Il faut refetch les charges
        
        -> mettre à jour ses informations personnelles.
          -> /users/:user (OK)

        -> créer un composant qui permet de mettre à jour les informations de la colocation
        -> créer un composant qui permet de supprimer un colocataire et de mettre à jour les charges en conséquence
        -> créer un composant qui permet de mettre à jour ses informations personnelles

    */
};
</script>
