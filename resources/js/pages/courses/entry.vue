<template>
  <div class="row">
    <div class="col">
      <card :title="formTitle">
        <template v-slot>
          <form @submit.prevent="createCourse" @keydown="form.onKeydown($event)">
            <!-- Title -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Title</label>
              <div class="col-md-7">
                <input v-model="form.title" :class="{ 'is-invalid': form.errors.has('title') }" class="form-control" type="text" name="title">
                <has-error :form="form" field="title" />
              </div>
            </div>
            <!-- Excerpt -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Excerpt</label>
              <div class="col-md-7">
                <textarea v-model="form.excerpt" :class="{ 'is-invalid': form.errors.has('excerpt') }" class="form-control" name="excerpt" />
                <has-error :form="form" field="excerpt" />
              </div>
            </div>
            <!-- Content -->
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Content</label>
              <div class="col-md-7">
                <textarea v-model="form.content" :class="{ 'is-invalid': form.errors.has('content') }" rows="6" class="form-control" name="content" />
                <has-error :form="form" field="content" />
              </div>
            </div>
            <!-- Submit Button -->
            <div class="form-group row">
              <div class="col-md-9 ml-md-auto">
                <v-button :loading="form.busy" type="success">
                  {{ formTitle }}
                </v-button>
              </div>
            </div>
          </form>
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'CourseEntry',
  middleware: 'auth',
  metaInfo () {
    return { title: 'Course Entry' }
  },
  data: () => ({
    form: new Form({
      title: '',
      excerpt: '',
      content: ''
    })
  }),
  computed: {
    formTitle () {
      return this.$route.query.id ? 'Edit Course' : 'Create Course'
    }
  },
  mounted () {
    if (this.$route.query.id) {
      this.getCourse()
    }
  },
  methods: {
    async createCourse () {
      try {
        let message = 'success'
        if (this.$route.query.id) {
          const { data } = await this.form.patch('/api/admin/course/' + this.$route.query.id)
          message = data.message
        } else {
          const { data } = await this.form.post('/api/admin/course')
          message = data.message
        }

        Swal.fire({
          title: 'Success',
          type: 'success',
          text: message,
          showConfirmButton: false,
          timer: 1500
        }).then(() => this.$router.push('/dashboard/courses'))
      } catch (err) {
        if (err.response.status === 422) {
          const { errors } = err.response.data
          const fields = Object.values(errors)
          let message = ''
          for (let i = 0; i < fields.length; i++) {
            for (let j = 0; j < fields[i].length; j++) {
              message += `<p class="text-danger">${fields[i][j]}</p>`
            }
          }
          Swal.fire({
            title: 'Error!',
            type: 'error',
            html: message
          })
        } else {
          Swal.fire({
            title: 'Error!',
            type: 'error',
            text: err.response.data.message
          })
        }
      }
    },
    // get course for edit
    async getCourse () {
      try {
        const { data } = await axios.get('/api/admin/course/' + this.$route.query.id + '/edit')

        this.form.keys().forEach(key => {
          this.form[key] = data[key]
        })
      } catch (e) {}
    }
  }
}
</script>
