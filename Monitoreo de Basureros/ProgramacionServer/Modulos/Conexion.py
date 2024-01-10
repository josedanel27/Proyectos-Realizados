#Conexion a la base de datos SQL Server

#Importando librerias necesarias
import pyodbc as db

#Creando variable conexion
server = "0.0.0.0"
database = "DB_RECOLECCION"
user = "SA"
contra = "Albondiga1!"

#Creando clase para interactuar con la base de datos
class conexion:

    #Funcion de iniciacion
    def __init__(self) -> None:
        cadena_conexion = f"DRIVER={{ODBC Driver 18 for SQL Server}}; SERVER={server}; DATABASE={database}; UID={user}; PWD={contra}; Encrypt=no"
        con = db.connect(cadena_conexion)
        self.conexion = con
        print("Conexion lista...")
    
    #Funcion de obtener basureros
    def Obtener_basureros(self):
        query = "SELECT * FROM V_Basureros"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        lista = []
        for fila in cursor.fetchall():
            lista.append(fila)
        self.conexion.commit()
        cursor.close()
        return lista
    
    #Iniciando session
    def Datos_Login(self, usuario, contrasena):
        query = f"EXEC PRO_SESSION '{usuario}','{contrasena}'"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        nombre = []
        try:
            for fila in cursor.fetchall():
                nombre.append(fila)
            
        except Exception as e:
            nombre = 'Error'
        self.conexion.commit()
        cursor.close()
   
        return nombre
    
    ###################### Funciones de add, edit y delete para las tablas ##########################

    #--------Seccion de usuarios -----#
    #Insertar
    def userinsert(self, usuario, nombre, psswd, rol):
        query = f"EXEC PRO_ADDUSER '{usuario}', '{psswd}', '{nombre}', '{rol}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #Actualizar
    def useredit(self, id, usuario, nombre, psswd, rol):
        query = f"PRO_EDITUSER '{id}', '{usuario}', '{psswd}', '{nombre}', '{rol}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #Eliminar
    def usedele(self, id):
        query = f"EXEC PRO_DELEUSER {id};"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #--------Seccion de Basureros -----#
    #Insertar
    def basuinsert(self, ubicacion, coordenadas, tipo, contenedores):
        query = f"PRO_ADDBASU '{ubicacion}', '{coordenadas}', '{tipo}', '{contenedores}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #Actualizar
    def basuedit(self, id, ubicacion, coordenadas, tipo, contenedores):
        query = f"PRO_EDITBASU '{id}', '{ubicacion}', '{coordenadas}', '{tipo}', '{contenedores}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #Eliminar
    def basudele(self, id):
        query = f"PRO_DELEBASU '{id}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()
    #--------Seccion de reportes-----#
    #Insertar
    def reportinsert(self, ubi, nombre, detalle, fecha, tipo):
        query = f"EXEC PRO_ADDREPORT '{ubi}', '{nombre}', '{detalle}', '{fecha}', '{tipo}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #Actualizar
    def reportedit(self, id, ubi, nombre, detalle, fecha, tipo):
        query = f"EXEC PRO_EDITREPORT '{id}', '{ubi}', '{nombre}', '{detalle}', '{fecha}', '{tipo}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    #Eliminar
    def reportdele(self, id):
        query = f"EXEC PRO_DELEREPORT '{id}'"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        self.conexion.commit()
        cursor.close()

    
    ############# Vistas a tablas ##################
    #Vistas para los reportes
    def viewreport(self):
        query = "SELECT * FROM V_REPORTES"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        reportes = []
        for registro in cursor.fetchall():
            reportes.append(registro)
        self.conexion.commit()
        cursor.close()
        return reportes
    
    def viewreporte(self, id):
        query = f"EXEC PRO_SELECREPORT '{id}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        registro = []
        for regis in cursor.fetchall():
            registro.append(regis)
        self.conexion.commit()
        cursor.close()
        return registro
    
    #Vistas para los usuarios
    def viewusers(self):
        query = "SELECT * FROM V_LISTAUSER;"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        registro = []
        for regis in cursor.fetchall():
            registro.append(regis)
        self.conexion.commit()
        cursor.close()
        return registro
    
    def viewuser(self, id):
        query = f"EXEC PRO_SELECUSER '{id}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        registro = []
        for regis in cursor.fetchall():
            registro.append(regis)
        self.conexion.commit()
        cursor.close()
        return registro
    
    #Vista para los basureros
    def viewbasureros(self):
        query = f"SELECT * FROM V_Basureros;"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        registro = []
        for regis in cursor.fetchall():
            registro.append(regis)
        self.conexion.commit()
        cursor.close()
        return registro
    
    def viewbasurero(self, id):
        query = f"EXEC PRO_SELECBASU '{id}';"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        registro = []
        for regis in cursor.fetchall():
            registro.append(regis)
        self.conexion.commit()
        cursor.close()
        return registro
    
    #Vista para inicializar software
    def inicio(self):
        query = f"SELECT * FROM V_BASUCOORD;"
        cursor = self.conexion.cursor()
        cursor.execute(query)
        registro = []
        for regis in cursor.fetchall():
            registro.append(regis)
        self.conexion.commit()
        cursor.close()
        return registro

    


