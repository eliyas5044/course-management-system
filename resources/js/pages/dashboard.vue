<template>
  <div class="jumbotron text-center">
    <h1 class="display-4">
      Welcome on Board!
    </h1>
    <hr class="my-4">
    <div>
      <router-link to="/dashboard/courses" class="btn btn-primary btn-large">
        Courses
      </router-link>
      <span class="mx-1" />
      <router-link to="/dashboard/lessons" class="btn btn-primary btn-large">
        Lessons
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: 'Dashboard' }
  }
}
</script>
