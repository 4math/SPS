<template>
  <div id="device-register">
    <b-modal
      id="register-modal"
      ref="modal"
      v-model="showValue"
      centered
      title="Submit Your Socket Info"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <div class="input-group">
          <b-form-group
            id="name-input-group"
            label="Name"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name-input"
              v-model="$v.form.name.$model"
              :state="validateState('name')"
              placeholder="My Socket"
              required
            />
          </b-form-group>

          <b-form-group
            id="desc-input-group"
            label="Description"
            label-for="desc-input"
          >
            <b-form-input id="desc-input" v-model="description" placeholder="It is my lovely socket" required />
          </b-form-group>

          <b-form-group
            id="socket-id-input-group"
            label="Socket ID"
            label-for="socket-id-input"
            invalid-feedback="Socket ID is required and it should be a 6 digit number"
          >
            <b-form-input
              id="socket-id-input"
              v-model="$v.form.socketID.$model"
              :state="validateState('socketID')"
              placeholder="123456"
              required
              aria-describedby="input-1-live-feedback"
            />
          </b-form-group>
        </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { validationMixin } from 'vuelidate'
export default {
  name: "DeviceRegister",
  mixins: [validationMixin],
  props: {
    show: {
      type: Boolean,
      required: true,
      default: false,
    },
  },
  
  data() {
    return {
      form: {
        name: null,
        socketID: null
      },
      description: '',
    };
  },
  validations: {
    form: {
      socketID: {
        type: Number,
        required
      },
      name: {
        type: String,
        required,
      }
    }
  },
  computed: {
    showValue: {
      get() {
        return this.show;
      },
      set(value) {
        this.$emit("closeModal", value);
      },
    },
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    resetModal() {
      this.form = {
        name: null,
        socketID: null
      };

      this.description = '';

      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    handleSubmit() {
      
      // don't submit if input is empty
      this.$v.form.$touch();
      if (this.$v.form.$anyError && this.$v.form.$anyDirty) {
        return;
      }
      // Hide the modal manually
      this.$nextTick(() => {
        this.$emit("registerSocket", this.form.name, this.description, this.form.socketID);
        this.$bvModal.hide("register-modal");
      });
    },
  },
};
</script>

<style scoped>
.form-group {
  width: 100%;
}
</style>
