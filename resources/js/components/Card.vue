<template>
  <Card class="flex flex-col items-center justify-center">
    <div class="px-3 py-3">
      <h1 class="text-center text-3xl text-gray-500 font-light" v-text="card.label"></h1>
      <h1 class="text-center text-1xl text-gray-500 font-light" v-text="card.sub_label"></h1>
    </div>
    <div class="w-full px-3 py-3">
      <button :disabled="!card.can_sync" @click="doSync(card.resource)"
              :class="{'bg-gray-300 rounded focus:outline-none': !card.can_sync, 'hover:bg-primary-400 active:bg-primary-600': card.can_sync}"
              class="w-full shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 text-white dark:text-gray-800 items-center font-bold px-4 h-9 text-sm">
        Sync <span v-text="getSyncTimesLabel()"></span>
      </button>


    </div>
  </Card>
</template>

<script>
export default {
  props: [
    'card',
  ],
  methods: {
    doSync(resource) {
      Nova.request().post('/nova-vendor/command-card/execute', {
        resource: resource
      });
      Nova.success('Syncing ' + resource);
    },
    getSyncTimesLabel() {
      return this.card.done + '/' + this.card.limit;
    }
  },

  mounted() {
    //
  },
}
</script>
