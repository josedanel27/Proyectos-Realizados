#Modulo para el inicio de sesion de la aplicacion

class login:

    def __init__(self, User):
        self.usuario = [User, '1234'] #Aqui debe ir la consulta a la base de datos
        return 0
    
    def validar(self, password):
        datos = self.usuario
        if datos != None:
            if datos[1] == password:
                return datos
        else:
            return None