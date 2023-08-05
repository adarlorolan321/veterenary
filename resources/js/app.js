
import { createApp, h } from "vue"
import { createInertiaApp, router as InertiaRouter } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m"
import "@/@iconify/icons-bundle"
import ability from "@/plugins/casl/ability"
import i18n from "@/plugins/i18n"
import layoutsPlugin from "@/plugins/layouts"
import vuetify from "@/plugins/vuetify"
import { loadFonts } from "@/plugins/webfontloader"

import { abilitiesPlugin } from "@casl/vue"
import "@core-scss/template/index.scss"
import "@styles/styles.scss"
import { createPinia } from "pinia"

import { can } from "@layouts/plugins/casl"


loadFonts()

const VITE_NOCAPTCHA_SITEKEY = import.meta.env.VITE_NOCAPTCHA_SITEKEY

const appName =
  window.document.getElementsByTagName("title")[0]?.innerText || "Laravel"

const fieldHasError = (errors, id) =>
  errors?.some(e => e.id == id) ? "input" : "submit"

const clearSelect = (form, name) => {
  form[name] = null
  form.clearErrors(name)
}

const onlyNumber = $event => {
  let keyCode = $event.keyCode ? $event.keyCode : $event.which
  if (keyCode < 47 || keyCode > 57) {
    // 46 is dot
    $event.preventDefault()
  }
}

const onlyNumberPrice = $event => {
  let keyCode = $event.keyCode ? $event.keyCode : $event.which
  if (keyCode < 46 || keyCode > 57) {
    // 46 is dot
    $event.preventDefault()
  }
}

const readOnlyField = $event => {
  $event.preventDefault()
}

const goBack = $event => {
  if (window.history.length && window.history.length > 1) {
    return window.history.back()
  }

  return InertiaRouter.visit("/")
}

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name =>
    resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob("./pages/**/*.vue"),
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(vuetify)
      .use(createPinia())
      .use(layoutsPlugin)
      
      .use(i18n)
      .directive("right-click", (el, binding) => {
        el.addEventListener("contextmenu", function (event) {
          event.preventDefault()
          binding.value(event)
        })
      })
      .mixin({
        methods: {
          can,
          fieldHasError,
          clearSelect,
          onlyNumber,
          onlyNumberPrice,
          readOnlyField,
          goBack,
        },
      })
      .use(abilitiesPlugin, ability, {
        useGlobalProperties: true,
      })
      
      .mount(el)
  },
  progress: {
    color: "#4B5563",
  },
})
