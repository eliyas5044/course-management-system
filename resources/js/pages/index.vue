<template>
  <router-view />
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: 'Dashboard' }
  }
}
</script>
