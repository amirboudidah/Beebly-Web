/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

/**
 *
 * @author rihem
 */
public class Evenement {

    private int id;
    private String libelle;
    private String date;

    private String description;

    private String emplacement;
        private int duree;
            private int nbPlace;
            private User idUser;

    public Evenement(int id, String libelle, String date, String description, String emplacement, int duree, int nbPlace, User idUser) {
        this.id = id;
        this.libelle = libelle;
        this.date = date;
        this.description = description;
        this.emplacement = emplacement;
        this.duree = duree;
        this.nbPlace = nbPlace;
        this.idUser = idUser;
    }

    public Evenement() {
    }

    public int getId() {
        return id;
    }

    public String getLibelle() {
        return libelle;
    }

    public String getDate() {
        return date;
    }

    public String getDescription() {
        return description;
    }

    public String getEmplacement() {
        return emplacement;
    }

    public int getDuree() {
        return duree;
    }

    public int getNbPlace() {
        return nbPlace;
    }

    public User getIdUser() {
        return idUser;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setLibelle(String libelle) {
        this.libelle = libelle;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public void setEmplacement(String emplacement) {
        this.emplacement = emplacement;
    }

    public void setDuree(int duree) {
        this.duree = duree;
    }

    public void setNbPlace(int nbPlace) {
        this.nbPlace = nbPlace;
    }

    public void setIdUser(User idUser) {
        this.idUser = idUser;
    }

    @Override
    public String toString() {
        return "Evenement{" + "id=" + id + ", libelle=" + libelle + ", date=" + date + ", description=" + description + ", emplacement=" + emplacement + ", duree=" + duree + ", nbPlace=" + nbPlace + ", idUser=" + idUser + '}';
    }
            
}
