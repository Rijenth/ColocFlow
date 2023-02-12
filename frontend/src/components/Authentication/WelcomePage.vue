<template>
  <div class="flex h-screen items-center justify-center">
    <div class="flex h-3/5 p-4 bg-gray-900 text-white rounded-xl shadow-xl">
      <div class="w-80">
        <h2 class="text-lg font-bold mb-2 text-center underline">
          Créer une colocation
        </h2>
        <p class="text-s mb-4"></p>
      </div>

      <div class="w-80 border-l-2">
        <h2 class="text-lg font-bold mb-2 text-center underline">
          Rejoindre une colocation
        </h2>
        <p class="text-s m-4 ml-6 align-middle text-left">
          Pour rejoindre une colocation existante, vous devez nous fournir le
          code confidentiel unique de cette colocation.
        </p>
        <p class="text-s m-4 ml-6 align-middle text-left">
          Si vous ne connaissez pas ce code, veuillez contacter le propriétaire
          de la colocation.
        </p>
        <div class="flex flex-col items-center align-middle mt-10">
          <input
            type="text"
            class="w-64 h-10 p-4 border-2 border-gray-900 rounded-lg"
            placeholder="Code confidentiel"
          />
          <button
            class="w-20 border h-10 bg-gray-900 text-white rounded-lg mt-4 hover:bg-gray-600"
          >
            Rejoindre
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
