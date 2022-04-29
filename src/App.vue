<template>
  <div class="container">
    <h2 class="mb-3" :class="headerClasses || 'h1'">{{ store.state.section_title }}</h2>
    <NewsDisplay v-if="isLoaded" />
    <div v-else class="row">
      <div class="col-3 col-md-2 col-lg-1 mx-auto">
        <div class="spin" :style="spinCssObj">
        </div>
      </div>
    </div>
    <a :href="store.state.button_href" target="_blank" rel="noopener" class="btn btn-primary mt-2" :class="buttonClasses">{{ store.state.button_text }}</a>
  </div>
</template>

<script setup>
import {computed, onMounted} from 'vue'
import {useStore} from 'vuex'

import NewsDisplay from './components/NewsDisplay.vue'

const store = useStore()

const headerClasses = computed(() => store.state.section_title_classes)
const buttonClasses = computed(() => store.state.button_classes)
const spinCssObj = computed(() => {
  return {
    'background-image': `url('${store.state.pluginUri}/img/cah-loading-icon.png')`,
    'background-repeat': 'no-repeat',
    'background-size': 'contain',
  }
})
const isLoaded = computed(() => store.state.isLoaded)

onMounted(() => {
  store.dispatch('doInit')
})
</script>

<style lang="scss">
h2 {
  text-transform: uppercase;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.spin {
  display: block;
  margin-top: 4em;
  width: 50px;
  height: 50px;
  animation-name: spin;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: ease;
}
</style>
