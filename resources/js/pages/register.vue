<script setup>
import { VForm } from "vuetify/components";
import authV2RegisterIllustrationBorderedDark from "@images/pages/auth-v2-register-illustration-bordered-dark.png";
import authV2RegisterIllustrationBorderedLight from "@images/pages/auth-v2-register-illustration-bordered-light.png";
import authV2RegisterIllustrationDark from "@images/pages/auth-v2-register-illustration-dark.png";
import authV2RegisterIllustrationLight from "@images/pages/auth-v2-register-illustration-light.png";
import authV2MaskDark from "@images/pages/misc-mask-dark.png";
import authV2MaskLight from "@images/pages/misc-mask-light.png";
import { useAppAbility } from "@/plugins/casl/useAppAbility";
import AuthProvider from "@/views/pages/authentication/AuthProvider.vue";
import axios from "@axios";
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import { themeConfig } from "@themeConfig";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
  alphaDashValidator,
  emailValidator,
  requiredValidator,
} from "@validators";

const refForm = ref();

const privacyPolicies = ref(true);

// Router
// Form Errors

const imageVariant = useGenerateImageVariant(
  authV2RegisterIllustrationLight,
  authV2RegisterIllustrationDark,
  authV2RegisterIllustrationBorderedLight,
  authV2RegisterIllustrationBorderedDark,
  true
);

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark);

const isPasswordVisible = ref(false);
const isPasswordConfirmVisible = ref(false);

const form = useForm({
  first_name: null,
  last_name: null,
  barangay: null,
  mobile_no: null,
  city: null,
  province: null,
  email: null,
  password: null,
  password_confirmation: null,
});

const submit = () => {
  refForm?.value?.validate().then((res) => {
    const { valid: isValid } = res;
    if (isValid) {
      form.post("/register", {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (data) => {
          console.log(data);
        },
      });
    }
  });
};
</script>

<template>
  <VRow no-gutters class="auth-wrapper">
    <VCol lg="6" class="d-none d-lg-flex">
      <div class="position-relative auth-bg rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="441"
            :src="imageVariant"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <VImg class="auth-footer-mask" :src="authThemeMask" />
      </div>
    </VCol>

    <VCol cols="12" lg="6" class="d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <VNodeRenderer :nodes="themeConfig.app.logo" class="mb-6" />
          <h5 class="text-h5 font-weight-semibold mb-1">
            Adventure starts here 🚀
          </h5>
          <p class="mb-0">Make your app management easy and fun!</p>
        </VCardText>

        <VCardText>
          <VForm ref="refForm" @submit.prevent="submit">
            <VRow>
              <!-- name -->
              <VCol cols="6">
                <VTextField
                  v-model="form.first_name"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="First Name"
                  :error-messages="form.errors.first_name"
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="form.last_name"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Last Name"
                  :error-messages="form.errors.last_name"
                />
              </VCol>
              <VCol cols="12">
                <VTextField
                  v-model="form.barangay"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Barangay"
                  :error-messages="form.errors.barangay"
                />
              </VCol>
               <VCol cols="6">
                <VTextField
                  v-model="form.city"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="City/Municipality"
                  :error-messages="form.errors.city"
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="form.province"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Province"
                  :error-messages="form.errors.province"
                />
              </VCol>
              <VCol cols="12">
                <VTextField
                  v-model="form.mobile_no"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Mobile No."
                  :error-messages="form.errors.mobile_no"
                />
              </VCol>

              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="form.email"
                  :rules="[requiredValidator, emailValidator]"
                  label="Email"
                  type="email"
                  :error-messages="form.errors.email"
                />
              </VCol>


              <!-- password -->
              <VCol cols="12">
                <VTextField
                  v-model="form.password"
                  :rules="[requiredValidator]"
                  :error-messages="form.errors.password"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
              </VCol>
              <VCol cols="12">
                <VTextField
                  v-model="form.password_confirmation"
                  :rules="[requiredValidator]"
                  label="Confirm Password"
                  :type="isPasswordConfirmVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordConfirmVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="
                    isPasswordConfirmVisible = !isPasswordConfirmVisible
                  "
                />

                <div class="d-flex align-center mt-2 mb-4">
                  <VCheckbox
                    id="privacy-policy"
                    v-model="privacyPolicies"
                    inline
                  />
                  <VLabel for="privacy-policy" class="pb-1" style="opacity: 1">
                    <span class="me-1">I agree to</span>
                    <a href="javascript:void(0)" class="text-primary"
                      >privacy policy & terms</a
                    >
                  </VLabel>
                </div>

                <VBtn block type="submit"> Sign up </VBtn>
              </VCol>

              <!-- create account -->
              <VCol cols="12" class="text-center text-base">
                <span>Already have an account?</span>
                <RouterLink class="text-primary ms-2" :to="{ name: 'login' }">
                  Sign in instead
                </RouterLink>
              </VCol>

              <VCol cols="12" class="d-flex align-center">
                <VDivider />
                <span class="mx-4">or</span>
                <VDivider />
              </VCol>

              <!-- auth providers -->
              <VCol cols="12" class="text-center">
                <AuthProvider />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
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
