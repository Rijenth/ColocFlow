<template>
  <div
    class="m-6 mt-0 mb-6 p-6 bg-gray-900 text-white border rounded-lg shadow-xl"
  >
    <h1 class="text-2xl text-left mb-4">À propos</h1>
    <p class="text-xs mb-4">
      Apprendre en pratiquant : Vue 3 | Dimanche 19 février 2023
    </p>
    <p class="text-xs mb-4">Vue 3 | TypeScript | Laravel | TailWindCSS</p>
    <p class="text-s mb-4">
      Ce projet est un projet personnel réalisé dans le cadre de mon
      apprentissage de Vue 3 et de TailWindCSS dans une approche mobile first.
    </p>

    <p class="text-s mb-4">
      Je continuerai à travailler sur ce projet pour l'améliorer et le parfaire
      jusqu'à ce que je sois satisfait des compétences acquises.
    </p>

    <p class="text-s mb-4">
      Si vous avez des suggestions ou des idées d'amélioration, n'hésitez pas à
      me contacter car il est toujours bon d'avoir des retours.
    </p>

    <p class="text-s mb-4">À bientôt !</p>

    <div class="flex">
      <a
        href="https://www.linkedin.com/in/rijentha/"
        class="w-1/2 bg-gray-800 mr-2 text-white p-2 px-3 rounded hover:bg-blue-800 text-center"
        target="_blank"
      >
        LinkedIn
      </a>

      <a
        href="https://github.com/Rijenth"
        class="w-1/2 bg-gray-800 mr-2 text-white p-2 px-3 rounded hover:bg-blue-800 text-center"
        target="_blank"
      >
        Github
      </a>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  name: "AboutView",
};
</script>
