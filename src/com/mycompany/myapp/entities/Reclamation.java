/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.myapp.entities;

/**
 *
 * @author Wael
 */
public class Reclamation {
    private int id ;
    private String type;
    private String sujet;
    private String description;
    private String date;
    private String photo;
    private String status;
    private User idUser;

    public Reclamation(int id, String type, String sujet, String description, String date, String photo, String status, User idUser) {
        this.id = id;
        this.type = type;
        this.sujet = sujet;
        this.description = description;
        this.date = date;
        this.photo = photo;
        this.status = status;
        this.idUser = idUser;
    }

    public Reclamation() {
    }

    public int getId() {
        return id;
    }

    public String getType() {
        return type;
    }

    public String getSujet() {
        return sujet;
    }

    public String getDescription() {
        return description;
    }

    public String getDate() {
        return date;
    }

    public String getPhoto() {
        return photo;
    }

    public String getStatus() {
        return status;
    }

    public User getIdUser() {
        return idUser;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setType(String type) {
        this.type = type;
    }

    public void setSujet(String sujet) {
        this.sujet = sujet;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public void setPhoto(String photo) {
        this.photo = photo;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public void setIdUser(User idUser) {
        this.idUser = idUser;
    }

    @Override
    public String toString() {
        return "Reclamation{" + "id=" + id + ", type=" + type + ", sujet=" + sujet + ", description=" + description + ", date=" + date + ", photo=" + photo + ", status=" + status + ", idUser=" + idUser + '}';
    }
    
    
    
    
    
}
