<script setup>
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant"
import { VNodeRenderer } from "@layouts/components/VNodeRenderer"
import { themeConfig } from "@themeConfig"
import authV2ForgotPasswordIllustrationDark from "@images/pages/auth-v2-forgot-password-illustration-dark.png"
import authV2ForgotPasswordIllustrationLight from "@images/pages/auth-v2-forgot-password-illustration-light.png"
import authV2MaskDark from "@images/pages/misc-mask-dark.png"
import authV2MaskLight from "@images/pages/misc-mask-light.png"
import { Head, Link, useForm } from "@inertiajs/vue3"
import { emailValidator, requiredValidator, passwordValidator, confirmedValidator } from '@validators';

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
})

const state = reactive({
  status: null,
})

const email = ref("")

const authThemeImg = useGenerateImageVariant(
  authV2ForgotPasswordIllustrationLight,
  authV2ForgotPasswordIllustrationDark,
)

const isConfirmPasswordVisible = ref(false)
const isPasswordVisible = ref(false)

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const refForm = ref(null)

const form = useForm({
  email: props.email,
  token: props.token,
  password: "",
  password_confirmation: "",
})

const submit = d => {
  refForm?.value?.validate().then(res => {
    const { valid: isValid } = res

    if (isValid) {
      form.post(route("password.store"), {
        // preserveScroll: true,
        // onSuccess: data => {
        //   form.reset('password', 'password_confirmation');
        //   if (data && data.props && data.props.status) {
        //     state.status = data.props.status;
        //   }
        //   form.reset('email');
        // },
      })
    }
  })
}
</script>

<template>
  <VRow
    class="auth-wrapper"
    no-gutters
  >
    <VCol
      lg="8"
      class="d-none d-lg-flex"
    >
      <div class="position-relative auth-bg rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="368"
            :src="authThemeImg"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <VImg
          class="auth-footer-mask"
          :src="authThemeMask"
        />
      </div>
    </VCol>

    <VCol
      cols="12"
      lg="4"
      class="d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText>
          <VNodeRenderer
            :nodes="themeConfig.app.logo"
            class="mb-6"
          />
          <h5 class="text-h5 font-weight-semibold mb-1">
            Forgot Password? 🔒
          </h5>
          <p class="mb-0">
            Enter your email and we'll send you instructions to reset your
            password
          </p>
        </VCardText>

        <VCardText>
          <VForm
            ref="refForm"
            validate-on="submit"
            @submit.prevent="submit"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="form.email"
                  label="Email"
                  :rules="[requiredValidator, emailValidator]"
                  :error-messages="form.errors.email"
                  readonly
                />
              </VCol>
            </VRow>
            <VRow>
              <VCol cols="12">
                <VTextField
                  id="password"
                  v-model="form.password"
                  :rules="[requiredValidator, passwordValidator]"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  :error-messages="form.errors.password"
                  :validate-on="
                    fieldHasError(refForm?.errors, 'password') == 'submit'
                      ? 'blur'
                      : 'input'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  @input="form.clearErrors('password')"
                />
              </VCol>
              <VCol cols="12">
                <VTextField
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :rules="[
                    requiredValidator,
                    confirmedValidator(
                      form.password,
                      form.password_confirmation
                    ),
                  ]"
                  label="Confirm Password"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  :error-messages="form.errors.password_confirmation"
                  :validate-on="
                    fieldHasError(refForm?.errors, 'password_confirmation') ==
                      'submit'
                      ? 'blur'
                      : 'input'
                  "
                  @click:append-inner="
                    isConfirmPasswordVisible = !isConfirmPasswordVisible
                  "
                  @input="form.clearErrors('password_confirmation')"
                />
              </VCol>
            </VRow>

            <VRow>
              <!-- Reset link -->
              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                  :disabled="form?.processing"
                  @click="refForm?.validate()"
                >
                  Reset password
                </VBtn>
              </VCol>

              <!-- back to login -->
              <VCol cols="12">
                <Link
                  class="d-flex align-center justify-center"
                  :href="route('login')"
                >
                  <VIcon
                    icon="tabler-chevron-left"
                    class="flip-in-rtl"
                  />
                  <span>Back to login</span>
                </Link>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
        <VDialog
          v-model="state.status"
          persistent
          width="500"
        >
          <!-- Dialog Content -->
          <VCard class="text-center">
            <VCardText class="d-flex flex-column justify-center align-center">
              <VAvatar
                color="success"
                variant="tonal"
                size="50"
                class="mb-4"
              >
                <VIcon
                  size="2rem"
                  icon="tabler-thumb-up"
                />
              </VAvatar>
              <h6 class="text-h6">
                Success
              </h6>
            </VCardText>
            <VCardText>
              <p>Your password has been reset!</p>
            </VCardText>
            <VCardActions class="justify-center">
              <Link
                class="d-flex align-center justify-center"
                :href="route('login')"
              >
                <VBtn variant="elevated">
                  Login
                </VBtn>
              </Link>
            </VCardActions>
          </VCard>
        </VDialog>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
