#Importando librerias necesarias para la accion
from flask import Flask, render_template
from flask import jsonify
from InteraccionesMouse import Control
import socket as sk

#Heredadando las clases
con = Control  #Control del cursos

#Obteniendo ip de maquina para enviar al frontend
ip = sk.gethostbyname(sk.gethostname())
#print(ip)

app = Flask(__name__)

@app.route('/')
def Control():
    data = {'ip': f'{ip}'}
    return render_template('index.html', data=data)

@app.route('/api/arriba/')
def arriba():
    con.Subir()
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

@app.route('/api/abajo/')
def abaja():
    con.Bajar()
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

@app.route('/api/derecha/')
def derec():
    con.Derecha()
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

@app.route('/api/izquierda/')
def izquier():
    con.Izquierda()
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

@app.route('/api/ok/')
def clic():
    con.Click(1)
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

@app.route('/api/sarriba/')
def Sarriba():
    con.Scroll(1)
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

@app.route('/api/sabajo/')
def sabajo():
    con.Scroll(2)
    resp = {'mensaje':'Correcto'}
    return jsonify(resp)

if __name__== "__main__":
    app.run(debug=True)