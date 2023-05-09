/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.myapp.entities;

/**
 *
 * @author Wael
 */
public class User {
   private int id;
    private int cin;
    private int soldepoint;
   private String nom;
    private String prenom;
     private String adrmail;
      private String adresse;
       private String mdp;
        private String tel;
         private String type;

    public User() {
    }

    public User(int id, int cin, int soldepoint, String nom, String prenom, String adrmail, String adresse, String mdp, String tel, String type) {
        this.id = id;
        this.cin = cin;
        this.soldepoint = soldepoint;
        this.nom = nom;
        this.prenom = prenom;
        this.adrmail = adrmail;
        this.adresse = adresse;
        this.mdp = mdp;
        this.tel = tel;
        this.type = type;
    }

    public int getId() {
        return id;
    }

    public int getCin() {
        return cin;
    }

    public int getSoldepoint() {
        return soldepoint;
    }

    public String getNom() {
        return nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public String getAdrmail() {
        return adrmail;
    }

    public String getAdresse() {
        return adresse;
    }

    public String getMdp() {
        return mdp;
    }

    public String getTel() {
        return tel;
    }

    public String getType() {
        return type;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setCin(int cin) {
        this.cin = cin;
    }

    public void setSoldepoint(int soldepoint) {
        this.soldepoint = soldepoint;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public void setAdrmail(String adrmail) {
        this.adrmail = adrmail;
    }

    public void setAdresse(String adresse) {
        this.adresse = adresse;
    }

    public void setMdp(String mdp) {
        this.mdp = mdp;
    }

    public void setTel(String tel) {
        this.tel = tel;
    }

    public void setType(String type) {
        this.type = type;
    }

    @Override
    public String toString() {
        return "User{" + "id=" + id + ", cin=" + cin + ", soldepoint=" + soldepoint + ", nom=" + nom + ", prenom=" + prenom + ", adrmail=" + adrmail + ", adresse=" + adresse + ", mdp=" + mdp + ", tel=" + tel + ", type=" + type + '}';
    }
         
}
