<script setup>
import navItems from '@/navigation/horizontal'
import { useThemeConfig } from '@core/composable/useThemeConfig'
import { themeConfig } from '@themeConfig'

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavBarI18n from '@/layouts/components/NavBarI18n.vue'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import NavbarShortcuts from '@/layouts/components/NavbarShortcuts.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import NavSearchBar from '@/layouts/components/NavSearchBar.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import { HorizontalNavLayout } from '@layouts'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'

const { appRouteTransition } = useThemeConfig()
</script>

<template>
  <VCard>
    <VLayout>
      <VAppBar
        :elevation="2"
        class="d-flex justify-end "
        theme="dark"
      >
        <!-- <NavbarThemeSwitcher /> -->
        <UserProfile class="ml-auto mr-2" />
      </VAppBar>
      <VNavigationDrawer
        class="bg-deep-purple"
        theme="dark"
        permanent
      >
        <VList class="menu" color="transparent">
          <VListItem
            
            prepend-icon="mdi-view-dashboard"
            title="Dashboard"
          />
          <VListItem
            prepend-icon="mdi-account-box"
            title="Account"
          />
          <VListItem
            prepend-icon="mdi-gavel"
            title="Admin"
          />
        </VList>
    
        <template #append>
          <div class="pa-2">
            <VBtn block>
              Logout
            </VBtn>
          </div>
        </template>
      </VNavigationDrawer>
        
      
      <VMain style="min-height: 100vh; " >
        <div class="custom-main">
          <VCard class="px-5 py-5">
            <slot />
          </VCard>
          
        </div>
       
      </VMain>

      <div class="main mt-5">
        
      </div>
    </VLayout>
  </VCard>
</template>

  
<script>
export default {
  data () {
    return {
      drawer: true,
      rail: true,
    }
  },
}
</script>

<style scoped>
 
   
</style>