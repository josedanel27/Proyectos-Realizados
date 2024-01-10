#Seccion para la generacion del mapa y la ruta

#importando librerias
import folium
from folium import plugins


#Creando clases para operar con la libreria y el software

class Mapa:

    #Funcion para crear mapa
    def __init__(self) -> None:
        #Inciando mapa
        map = folium.Map(location=[8.101975, -80.973231], zoom_start=16)
        self.mapa = map

    #Funcion para cargar ubicaciones de los basureros en el mapa
    def Cargar_Ubicaciones(self, coordenadas):
        mapa = self.mapa
        #Creando puntos en el mapa
        for a, x, y in coordenadas:
            folium.Marker([x, y], popup=a).add_to(mapa)
        #capa = mapa._repr_html_()
        self.locales=mapa
        return #capa
    
    def ruta(self, estatus, datos):
        #Arreglo de posicion
        relevante = []
        #Promediando niveles
        for a, b, c, d, e in datos:
            i = 0
            suma = 0
            while i <= len(estatus)-1:
                if estatus[i][0] == a:
                    suma += estatus[i][1]
                i += 1
            promedio = (suma * 100) / ( e * 100 )
            relevante.append([c, d, promedio])
        
        #Ordenando de mayor a menos en base al porcentaje
        relevant = sorted(relevante, key=lambda x: x[2])
        
        #Trazando ruta en el mapa
        rutas = []
        i = 1
        while i < len(relevant):
            rutas.append([[relevant[i-1][0], relevant[i-1][1]], [relevant[i][0], relevant[i][1]]])
            i += 1
        print(rutas)
        #Añadiendo lineas al mapa
        map = self.locales
        for registro in rutas:
            folium.PolyLine(locations=registro, color="red", weight=15, opacity=0.8, options={"type": "driving"}).add_to(map)
        capa=map._repr_html_()
        return capa
        
            
        

            
        
        
