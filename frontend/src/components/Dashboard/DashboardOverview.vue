<template>
  <div
    class="w-120 mb-6 p-6 bg-gray-700 text-white border rounded-lg shadow-xl"
  >
    <h2 class="text-lg font-bold mb-2">Bienvenue sur ColocFlow</h2>
    <p class="text-s mb-4">Votre logiciel de gestion de colocation</p>
    <p>Dashboard General administrateur</p>
  </div>
</template>

<script lang="ts">
/* 
  todo: 
    - Etat général de la colocation
      -> Nombre de colocataires
      -> Nombre de tâches
      -> Nombre de dépenses
      -> Nombre de rapports d'incident
      -> Nombre de propositions de colocataires
      -> Etat de payements des dépenses ( tableau ? )
      si admin :
        -> Option de modification de la colocation
        -> Option de suppression de la colocation
      si colocataire :
        -> tâche à effectué
        -> dépenses à payer
        -> rapports d'incident à lire
        -> propositions de colocataires à lire

*/
export default {
  name: "DashboardOverview",
};
</script>
