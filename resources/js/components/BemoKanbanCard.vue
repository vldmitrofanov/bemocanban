<template>
  <li class="card">
    <button
      type="button"
      class="btn-close"
      aria-label="Close"
      @click="deleteCard"
    ></button>
    <a href="#" @click.stop.prevent="editCard">
      {{ card.title }}
    </a>
  </li>
</template>
<script>
export default {
  props: {
    card: {
      required: true,
      type: Object,
    },
  },
  methods: {
    editCard() {
      this.$emit("editCard", this.card);
    },
    deleteCard() {
      if (confirm("Realy delete?")) {
        this.realDeleteCard();
      }
    },
    async realDeleteCard() {
      await axios.delete(`/api/card/${this.card.id}`);
      this.$emit("update");
    },
  },
};
</script>
<style lang="scss">
.card {
  .btn-close {
    font-size: 12px!important;
    top: 4px!important;
    right: 4px!important;
  }
}
</style>