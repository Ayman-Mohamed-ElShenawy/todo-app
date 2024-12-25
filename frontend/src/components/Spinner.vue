<template>
  <div>
    <div class="spinner-grow text-light" role="status">
  <span class="visually-hidden" >Loading...</span>
</div>
  </div>
</template>

<script>

export default {
    name:'spinner-component',
    data(){
        return{
        }
    }
    
}
</script>


<style lang="css">
.border-color-none:focus {
  border-color: white;
}
.spinner-grow{
  width: 10px !important;
  height: 10px !important;
}
</style>