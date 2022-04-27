/* eslint no-undef: 1 */
import { createStore } from 'vuex'

import _ from 'lodash'
import axios from 'axios'

const state = {
  restUri: wpVars.restUri,
  ajaxUrl: wpVars.ajaxUrl,
  nonce: wpVars.wpNonce,
  news: {},
  dept: 11,
  limit: -1,
  per_page: 20,
  view: 'full',
  cat: [],
  exclude: [],
  section_title: "In the News",
  section_title_classes: '',
  button_text: "More News",
  button_classes: '',
  button_href: '',
  new_tab: false,
  tags: '',
}

export default createStore({
  state,
  getters: {
    displayList: state => {
      return Object.values(state.news)
        .sort((a, b) => {
          const dateA = new Date(a.date)
          const dateB = new Date(b.date)

          return dateB - dateA
        })
        .map(item => item.id)
    },
    getNewsItem: state => (id) => state.news[id],
  },
  mutations: {
    updateState(state, {name, value}) {
      if (state[name] === undefined) {
        const newState = state
        newState[name] = value
        state = newState
      } else {
        state[name] = value
      }
    }
  },
  actions: {
    async getOptions({commit}) {
      const options = JSON.parse(_.unescape(document.querySelector('#cah-news-vue-options').value))

      for (const [key, value] of Object.entries(options)) {
        commit('updateState', {name: key, value})
      }
    },
    async getNews({commit, state}) {
      const url = state.ajaxUrl

      const dept = state.dept.length ? (Array.isArray(state.dept) ? state.dept.join(',') : state.dept) : ''

      const options = {
        dept,
        tags: state.tags,
        per_page: state.per_page,
      }

      const restRequest = _.escape(`${state.restUri}/news?${Object.entries(options).filter(([,value]) => (!isNaN(value) && value > 0) || ((isNaN(value) || Array.isArray(value)) && value?.length)).map(([key, value]) => `${key}=${value}`).join('&')}`)

      const data = {
        action: 'cah-news',
        wpNonce: state.nonce,
        restRequest,
      }

      const formData = new FormData()

      for (const [key, value] of Object.entries(data)) {
        formData.append(key, value)
      }

      const resp = await axios.post(url, formData)
        .then(response => response.data)

      commit('updateState', {name: 'news', value: resp})
    },
    async doInit({dispatch}) {
      await dispatch('getOptions')
      dispatch('getNews')
    },
  },
  modules: {}
})
