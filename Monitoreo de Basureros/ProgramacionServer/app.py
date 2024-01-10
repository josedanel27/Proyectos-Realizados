#Codigo inicial Proyecto - Metodologias


#Importando librerias o modulos
from flask import Flask, session
from flask import render_template, redirect
from flask import request
from flask_bootstrap import Bootstrap
from Modulos.Listas import Listas
from Modulos.Conexion import conexion
from Modulos.Funciones import Funciones
from Modulos.mapa import Mapa

#Variables de iniciacion
db = conexion()
Coordenadas_Basureros = []
Estatus = []
Datos_basurero = []
SecretKey = Funciones.Gen_Key()

#Funciones del programa
def cargar_datos():
    consulta = db.inicio()
    Coordenadas_Basureros, Estatus, Datos_basurero = Funciones.seccionar_datos(consulta)
    return Coordenadas_Basureros, Estatus, Datos_basurero

def actualizar_estado():
    Funciones.actualizar_niveles(Estatus, Datos_basurero)
    
    


#Iniciando variable con la cable de Flask
app = Flask(__name__)
Bootstrap(app)
app.config['BOOTSTRAP_SERVE_LOCAL'] = True
app.config["SECRET_KEY"] = SecretKey
Coordenadas_Basureros, Estatus, Datos_basurero = cargar_datos()




#Asignando enrutatmiento a la pagina principal 
@app.route("/")
def inicio():
    
    return render_template("inicio.html")

############Asignando rutas para la aplicacion##############
#--------Seccion control de acceso a la aplicacion-------
#Inicio de sesion
@app.route("/Login")
def login():
    return render_template("login.html")

#Cierre de la sesion
@app.route("/logout")
def cerrar():
    session.clear()
    return redirect('/')

#Validar datos para el inicio de sesion
@app.route("/Check", methods=['POST'])
def Validacion():
    if request.method == 'POST':
        user = request.form['Username']
        pswd = request.form['Password']
        datos = db.Datos_Login(usuario=user, contrasena=pswd)
        if datos != []:
            session['id'] = datos[0][0]
            session["username"] = datos[0][1]
            session["rol"] = datos[0][2]
            print(session["rol"])
            return redirect('/Monitoreo')
        else:
            return redirect("/Login")

    return redirect("/Login")
    
#-------Secion de las funciones de la aplicacion------
#pagina principal con informacion general
@app.route("/Monitoreo")
def Monitoreo():
    if "username" in session:
        actualizar_estado()
        datos = Datos_basurero
        basus = []
        for a, b, c, d, e in datos:
            basus.append([a, b, e])
        estado = Estatus
        return render_template("dashboard.html", basures=basus, estdo=estado, usr=session["username"])
    else:
        return redirect('/Login')

#pagina para generar ruta de recoleccion y mostrarla
@app.route("/Ruta")
def Ruta(): 
    if "username" in session:
        mapa = Mapa()
        mapa.Cargar_Ubicaciones(Coordenadas_Basureros)
        map = mapa.ruta(Estatus, Datos_basurero)
        return render_template('ruta.html', usr=session["username"], mapita=map)
    else:
        return redirect('/Login')

#------Seccion de paginas para la generacion de reporte-------
@app.route("/Reporte+")
def Reporte():
    if "username" in session:
        return render_template('reporte.html', usr=session["username"], fecha=Funciones.Fecha())
    else:
        return redirect('/Login')

@app.route('/ReporteFrec')
def FrecReport():
    if "username" in session:
        return render_template('reportfrec.html', usr=session["username"])
    else:
        return redirect('/Login')

@app.route('/ListReport')
def Lista():
    if "username" in session:
        lista = db.viewreport()
        return render_template('listreport.html', reportes=lista, usr=session["username"])
    else:
        return redirect('/Login')

@app.route('/AccionReport', methods=['POST'])
def reportaccion():
    if "username" in session and session["rol"] == 2:
        if request.method == 'POST':
            accion = request.form
            if 'borrar' in accion:
                print(f"Borrar {accion['borrar']}")
                db.reportdele(accion['borrar'])
        return redirect('/ListReport')
    elif "username" in session:
        return redirect('/Monitoreo')
    else:
        return redirect('/Login')
    
@app.route('/addreport', methods=['POST'])
def adreport():
    if "username" in session:
        if request.method == 'POST':
            accion = request.form
            titulo = accion['titulo']
            fecha = accion['fecha']
            Detalle = accion['Incidente']
            tipo = accion['inputState']
            db.reportinsert('Desconocida', titulo, Detalle, fecha, tipo)
            return redirect('/ListReport')
    else:
        return redirect('/Login')


#Seccion sobre descripcion los miembros del proyecto
@app.route("/about")
def Sobre():
    return render_template('about.html')

#-----Session de paginas para la configuracion-----
@app.route("/Configuracion")
def Config():
    if "username" in session and session["rol"] == 2:
        lista = db.viewusers()
        return render_template('ajustes.html', user=lista, usr=session["username"])
    elif "username" in session:
        return redirect('/Monitoreo')
    else:
        return redirect('/Login')

#Edicion de usuarios
@app.route('/EditUser', methods=['POST'])
def editaruser():
    if "username" in session and session["rol"] == 2:
        if request.method == 'POST':
            accion = request.form
            id = accion['user']
            nombre = accion['nombre']
            usuario = accion['username']
            contrasena = accion['passwd']
            if accion['rol'] == 'Monitor':
                rol = 1
            elif accion['rol'] == 'Admin':
                rol = 2
            db.useredit(id, usuario, nombre, contrasena, rol)
            return redirect('/Configuracion')
    elif "username" in session and session["rol"] == 2:
        return redirect('/Monitoreo')
    else:
        return redirect('/Login')

#----Seccion lista de basureros----
@app.route('/Basureros')
def basureros():
    if "username" in session:
        datos = db.viewbasureros()
        return render_template('listbasu.html',basureros=datos, usr=session["username"])
    else:
        return redirect('/Login')

@app.route('/AccionBasu',methods=['POST'])
def Acciones_Basurero():
    if "username" in session and session["rol"] == 2:
        if request.method == 'POST':
            accion = request.form
            if 'borrar' in accion:
                db.basudele(accion['borrar'])
                return redirect('/Basureros')
            elif 'editar' in accion:
                registro = db.viewbasurero(accion['editar'])
                basu = []
                for regi in registro:
                    basu.append(regi)
                cargar_datos()
                return render_template('editarbasu.html', usuario=session['username'], id=accion['editar'], basurero=basu)
    elif "username" in session:
        return redirect("/Monitoreo")
    else:
        return redirect('/Login')

@app.route('/editbasu', methods=['POST'])
def edibasu():
    if "username" in session and session["rol"] == 2:
        if request.method == 'POST':
            accion = request.form
            print(accion)
            ubicacion = accion['ubicacion']
            numcont = accion['contenedores']
            coordenadas = accion['coordenadas']
            if accion['tipo'] == 'Reciclaje':
                tipo = 1
            else:
                tipo = 2
            db.basuedit(accion['basu'],ubicacion, coordenadas, tipo, numcont)
            return redirect('/Basureros')
    elif "username" in session:
        redirect('/Monitoreo')
    else:
        return redirect('/Login')

#-----------Acciones para ajustes ------------#
@app.route('/AccionAjuste', methods=['POST'])
def Acciones_Ajustes():
    if "username" in session and session["rol"] == 2:
        
        if request.method == 'POST':
            accion = request.form
            
            if 'user' in accion:
                nombre = accion['nombre']
                usuario = accion['username']
                contrasena = accion['passwd']
                if accion['rol'] == 'Roles' or accion['rol'] == 'Admin':
                    rol = 1
                db.userinsert(usuario, nombre, contrasena, rol)
            
            elif 'basu' in accion:
                ubicacion = accion['ubicacion']
                numcont = accion['contenedores']
                coordenadas = accion['coordenadas']
                if accion['tipo'] == 'Normal':
                    tipo = 2
                else:
                    tipo = 1
                db.basuinsert(ubicacion, coordenadas, tipo, numcont)
            
            elif 'borrar' in accion:
                db.usedele(accion['borrar'])

            elif 'editar' in accion:
                user = []
                registro = db.viewuser(accion['editar'])
                for us in registro:
                    user.append(us)
                return render_template('edituser.html', use=user, usuario=session['username'], id=accion['editar'])
                

        return redirect('/Configuracion')
    elif "username" in session:
        redirect('/Monitoreo')
    else:
        return redirect('/Login')

#!!!!!!!!!!!!!!Borrar despues usuario de desarrollo!!!!!!!!!!!!!!!!!!!!!!
@app.route('/Desarrollo')
def desarrollo():
    session["username"] = "Desarrollo"
    session['rol'] = 2
    return redirect('/Monitoreo')


#Comprobando que el archivo es el principal
if __name__ == "__main__":
    #Iniciando servidor
    app.run(port = 2727, debug = True)