<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Usuario</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <i class="fa fa-align-justify fa-2x ml-1 mr-1"></i>
                        <h3 class="">Crear Actividad</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post" class="form-horizontal">
                        <div class="form-group row mb-2">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <i class="fa fa-book fa-2x"></i>
                                    <input type="text" class="form-control ml-2" v-model="username" placeholder="Nombre de la actividad"
                                        id="activity">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit" @click="registerUser()">
                    <i class="fa fa-dot-circle-o"></i> Agregar </button>
                    <button class="btn btn-sm btn-danger" type="reset">
                    <i class="fa fa-ban"></i> Cancelar </button>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
    export default {
        data(){
            return{
                //Global Variables
                activity: '',

                arrayUser: [],
                arrayRoles: [],

                //Validate User
                userError: 0,
                userShowErrorMsg: [],
            }
        },
        methods:{
            listRoles(){
                let me = this;
                axios.get('/admin/selectRole/').then(function (response){
                    //Executed Succesfully
                    me.arrayRoles = response.data.roles;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            registerUser(){

                if (this.validateUser()) {
                    return;
                }

                let me = this;
                axios.post('/admin/usuario/registrar', {
                    'username': this.username,
                    'password': this.password,
                    'name': this.name,
                    'id_role': this.role.id
                }).then(function (response){
                    //Executed Succesfully
                    Swal.fire({
                    type: 'success',
                    title: 'Éxito!',
                    text: 'Tu registro se ha creado correctamente!',
                    showConfirmButton: false,
                    timer: 2500
                    })
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 2500
                    })
                });

            },
            validateUser(){
                this.userError=0;
                this.userShowErrorMsg = [];

                if(!this.name) this.userShowErrorMsg.push('El Nombre del Usuario no puede estar vacío. </br> ');
                if(!this.username) this.userShowErrorMsg.push('El número de control/clave no puede estar vacío. </br>');
                if(!this.password) this.userShowErrorMsg.push('La contraseña no puede estar vacía. </br>');
                if(!this.role) this.userShowErrorMsg.push('El rol del usuario no puede estar vacío. </br>');
                if (this.userShowErrorMsg.length) this.userError = 1;
                
                if (this.userShowErrorMsg.length >= 1){
                    toastr.error(this.userShowErrorMsg, 'Error!', {timeOut: 5000});
                }
                return this.userError;
            }
        },
        mounted() {
            this.listRoles();
        }
    }
</script>