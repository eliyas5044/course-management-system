<template>
  <div class="card">
    <div v-if="title" class="card-header d-flex align-items-center">
      <div>{{ title }}</div>
      <div style="flex-grow: 1;" />
      <slot name="action" />
    </div>

    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null }
  }
}
</script>
