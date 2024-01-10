#Modulo para acceder a la base de datos y listar los items necesarios

class Listas:

    def Basureros():
        Lista_basureros = [
            ['1', 'Calle 8', '1', '2'],
            ['2', 'Calle 9', '2', '1'],
            ['3', 'Calle 4b', '2', '3'],
            ['4', 'Calle 2', '1', '1'],
            ['5', 'Calle 6a', '1', '1'],
            ['6', 'Calle 10', '2', '2']
        ]
        return Lista_basureros
    
    def Reportes():
        Lista_Reportes = [
            ['1', 'Vaciado Calle8', 'No notifica que se vacio', '2023-10-12'],
            ['2', 'Error de conexion', 'No envia señal', '2023-10-2'],
            ['3', 'Llenado calle 9', 'Se desbordo', '2023-1-12'],
            ['4', 'Actualizacion calle 8', 'No se actualizo el tipo', '2023-12-1']
        ]
        return Lista_Reportes
    
    def status():
        estado = [
            ['1', 25],
            ['1', 50],
            ['2', 34],
            ['3', 41],
            ['3', 15],
            ['3', 87],
            ['4', 76],
            ['5', 90],
            ['6', 28],
            ['6', 61]
        ]
        return estado
    
    def user():
        usuario = [
            ['jpuser', 'dondees', '0', '1', 'Jose Peñalba'],
            ['dcuser', 'quees', '1', '1', 'Delvis Cruz'],
            ['cguser', 'aymadre', '2', '1', 'Carla Garzon']
        ]
        return usuario

    
