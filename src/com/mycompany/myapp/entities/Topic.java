/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.myapp.entities;

/**
 *
 * @author Wael
 */
public class Topic {
    private int idtopic;
    private String titretopic;
    private String description;
    private String date;
    private boolean accepter;
    private int nbsujet;
    private int hide;
    private User iduser;

    public Topic(int idtopic, String titretopic, String description, String date, boolean accepter, int nbsujet, int hide, User iduser) {
        this.idtopic = idtopic;
        this.titretopic = titretopic;
        this.description = description;
        this.date = date;
        this.accepter = accepter;
        this.nbsujet = nbsujet;
        this.hide = hide;
        this.iduser = iduser;
    }

    public Topic() {
    }

    public int getIdtopic() {
        return idtopic;
    }

    public String getTitretopic() {
        return titretopic;
    }

    public String getDescription() {
        return description;
    }

    public String getDate() {
        return date;
    }

    public boolean isAccepter() {
        return accepter;
    }

    public int getNbsujet() {
        return nbsujet;
    }

    public int getHide() {
        return hide;
    }

    public User getIduser() {
        return iduser;
    }

    public void setIdtopic(int idtopic) {
        this.idtopic = idtopic;
    }

    public void setTitretopic(String titretopic) {
        this.titretopic = titretopic;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public void setAccepter(boolean accepter) {
        this.accepter = accepter;
    }

    public void setNbsujet(int nbsujet) {
        this.nbsujet = nbsujet;
    }

    public void setHide(int hide) {
        this.hide = hide;
    }

    public void setIduser(User iduser) {
        this.iduser = iduser;
    }

    @Override
    public String toString() {
        return "Topic{" + "idtopic=" + idtopic + ", titretopic=" + titretopic + ", description=" + description + ", date=" + date + ", accepter=" + accepter + ", nbsujet=" + nbsujet + ", hide=" + hide + ", iduser=" + iduser + '}';
    }
    
}
