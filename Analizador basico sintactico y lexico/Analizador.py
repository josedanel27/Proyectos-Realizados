"""
    Desarrolle un programa el cual permita al usuario ingresar una línea de código de un lenguaje de programación.

"""

#Importando librerias necesarias

import re
import tkinter as tk
from tkinter import *
from tkinter import ttk





def validar(palabra):
    
    #Expresion para validar
    expreprint  =   '(printf([ ])*[(]([ ])*(("[ a-z0-9A-Z%]{0,}")?)([ ])*((&[a-zA-Z]{0,}[0-9]{0,})?([ ])*(,([ ])*&[a-zA-Z]{0,}[0-9]{0,})*)+([ ])*[)]([ ])*;)'
    exprescan   =   '(scanf([ ])*[(]([ ])*(("[ a-z0-9A-Z]{0,}")?([ ])*(&[a-zA-Z]{0,}[0-9]{0,})?(,([ ])*&[a-zA-Z]{0,}[0-9]{0,})*)+([ ])*[)]([ ])*;)'
    exprefun    =   '((void|int|char)([ ])*([a-zA-Z]{0,}[0-9]{0,})([ ])*([(]([ ])*(([a-zA-Z]{0,}[0-9]{0,})([ ])*(,([ ])*[a-zA-Z]{0,}[0-9]{0,})?)?([ ])*[)])([ ])*[{]([a-z0-9-A-Z =]{0,};)*[}])'
    #expreswi    =   '(switch ([a-zA-Z]{0,}[0-9]{0,})([(]([a-z0-9A-Z ]{0,}){0,1}[)])[{](case ["][a-z0-9A-Z]["][:]([ a-z0-9A-Z =]{0,});)*[}])'
    cadenafinal =   f"({expreprint}|{exprefun}|{exprescan})"
    #Validación de linea de codigo
    resultados =re.fullmatch(cadenafinal, palabra)

    if (resultados):
        return "Cadena Valida"
    else:
        return "Cadena no valida"

#Funcion para analisis lexico
def lexico (palabra):
    
    reservadas = ('printf', 'scanf', 'char','void', 'if', 'else', 'else if', 'switch', 'case','int')
    tokens = (
        ('+', 'Adicion'),
        (',','Coma'),
        ('(', 'LParentesis'),
        (')', 'RParentesis'),
        ('-', 'Sustraccion'),
        ('=', 'Asignacion'),
        ('<>', 'NE'),
        ('<', 'LT'),
        ('<=', 'LTE'),
        ('>', 'GT'),
        ('>=', 'GTE'),
        (';', 'PuntoComa'),
    )

    #Expresion para encontrar identificadores
    identificadores = '[&]{0,1}[a-zA-Z]{0,}[0-9]{0,}'
    cadena_Impresion = '("[ a-z0-9A-Z%]{0,}")'
    #Vector para almacenar las palabras reservadas o token que se van encontrando
    arreglo = []

    #-----------------Inciando Validacion-----------------------
    i = 0
    #Buscando palabras reservadas
    for reservada in reservadas:
        
        if(reservada in palabra):
            arreglo.append(('0', 'Reservada', reservada))
            break

    #Buscando tokens u Operadores
    i = 0
    while (i < 12):
        if(tokens[i][0] in palabra):
            arreglo.append(('0', tokens[i][1], tokens[i][0]))
        i += 1
    

    #Buscando Identificadores o textos en pantalla
    cadena_dividida = palabra.split()
    print(cadena_dividida)
    for text in cadena_dividida:
        if(re.fullmatch(identificadores,text)):
            arreglo.append(('0', 'Identificador', text))
        
        if(re.fullmatch(cadena_Impresion, text)):
            arreglo.append(('0', 'Impresion', text))

    return arreglo


#Funcion para aniadir espacios
def spaces(Marco, alto):
    algo = Frame(Marco)
    algo.config(height=alto)
    algo.pack(side='top')

def ActionButton(entrada, salida):
    salida.delete(1.0,END)
    salida.insert(1.0,validar(entrada))

def TabLexe(palabra, objeto):
    objeto.delete(*objeto.get_children())
    matriz  = lexico(palabra)
    for item in matriz:
        objeto.insert('', tk.END, values=item)
    objeto.pack(side='left')

    

def interfaz():
    #Variables para creacion de entorno grafico
    app = Tk()
    app.geometry('800x750')
    app.title('Parcial #3 Derek Tristan, Jose Peñalba')
    ventana = Frame(app)
    ventana.pack(side='top')


    #Definiendo interfaz
    
    #titulo
    spaces(ventana,14)
    titulo = Frame(ventana)
    titulo.pack(side=TOP)
    LabelTitulo = Label(titulo,text="Parcial #2",font='Arial 20 bold')
    LabelTitulo.pack()

    #Funciones aceptadas
    spaces(ventana,18)
    FunAcep =   Frame(ventana)
    FunAcep.pack(side=TOP, expand=TRUE)
    LabelFun    =   Label(FunAcep,text='Funciones Aceptadas:  ', font='Arial 14 bold')
    LabelFun.pack(side = 'left')
    
    LabelFunAcep    =   Label(FunAcep, text='Imprimir texto, definir funciones, scanf', font='Arial 12')
    LabelFunAcep.pack(side='right')
    
    #Textbox para introduccion de la linea de codigo
    spaces(ventana,18)
    CajaTexto   =   Frame(ventana)
    CajaTexto.pack(side='top',expand=TRUE)
    
    LabelInput  =   Label(CajaTexto,text='Introduzca su linea de codigo: ',font='Arial 14')
    LabelInput.pack(side='left')

    textinput   =   Entry(CajaTexto,width=50)
    textinput.config(font='Arial 12')
    textinput.pack(side='right')

    #Boton para validar
    spaces(ventana, 22)
    SetBoton    =   Frame(ventana)
    SetBoton.pack(side='top',expand=TRUE)

    ValiBoton   =   Button(SetBoton, text='Validar', borderwidth=0, height=1, font='Arial 16 bold', bg='skyblue3', width=5, command=lambda:ActionButton(textinput.get(),TextOut))
    ValiBoton.pack()

    #Seccion de impresion de salida sintaxis
    spaces(ventana,25)
    InfoOut =   Frame(ventana)
    InfoOut.pack(side='top',expand=TRUE)

    LabelOut = Label(InfoOut,text='Resultado analisis de Sintaxis',font='Arial 16 bold')
    LabelOut.pack(side='top')
    spaces(InfoOut,10)
    TextOut =   Text(InfoOut, width=40,height=1)
    TextOut.config(font='Arial 14')
    TextOut.insert(1.0, 'Click boton validar')
    TextOut.pack(side='top')

    #Seccion de impresion de salida lexico
    spaces(ventana,25)
    InfoLexi = Frame(ventana)
    InfoLexi.pack(side='top', expand=TRUE)
    
    LabelLexi = Label(InfoLexi,text='Resultado analisis lexico', font='Arial 16 bold')
    LabelLexi.pack(side='top')
    spaces(InfoLexi,10)
    #Creando vista tabular de datos
    FrameTabla = Frame(InfoLexi)
    FrameTabla.pack(side='top')
    columnas = ('num', 'tipo', 'token')
    tabla = ttk.Treeview(FrameTabla, columns=columnas, show='headings')
    tabla.heading('num',text='#')
    tabla.heading('tipo', text='Tipo')
    tabla.heading('token', text='Token')
    """
    tokens = []
    for n in range(1, 100):
        tokens.append((f'first {n}', f'last {n}', f'email{n}@example.com'))
    #tokens.append(('1','prueba', 'prueba'))

    for tok in tokens:
        tabla.insert('', tk.END, values=tok)"""
    tabla.pack(side='left')
    #scroll
    scrollbar = ttk.Scrollbar(FrameTabla, orient=tk.VERTICAL, command=tabla.yview)
    tabla.configure(yscroll=scrollbar.set)
    scrollbar.pack(side='right')
    #Boton para validar
    spaces(ventana, 22)
    SetBotonLe   =   Frame(ventana)
    SetBotonLe.pack(side='top',expand=TRUE)

    ValiBotonLe   =   Button(SetBotonLe, text='Validar', borderwidth=0, height=1, font='Arial 16 bold', bg='skyblue3', width=5, command=lambda:TabLexe(textinput.get(),tabla))
    ValiBotonLe.pack()


    #footer
    PieInterfaz = Frame(app)
    PieInterfaz.pack(side='bottom')
    LabelFinal = Label(PieInterfaz,text='Hecho por: Derek Tristan y Jose Peñalba | Todos los derechos reservados!', font='Arial 12 italic')
    LabelFinal.pack()

    

    app.mainloop()
    


"""
#Seccion para prueba
cadena = input("Ingrese la linea de codigo: ")
print(validar(cadena))

"""
#Prueba de interafaz
interfaz()
