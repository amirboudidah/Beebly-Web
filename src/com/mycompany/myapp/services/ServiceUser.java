/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.myapp.services;

import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.entities.Evenement;
import com.mycompany.myapp.entities.User;
import static com.mycompany.myapp.services.ServiceEvenement.BASE_URL;
import static com.mycompany.myapp.services.ServiceTopic.BASE_URL;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author Wael
 */
public class ServiceUser {
        public ArrayList<User> lists;
    public static ServiceUser instance ; 
    public boolean resultOK;
    private  ConnectionRequest req; 
    public static User connectedUser ;
 public static final String BASE_URL="http://127.0.0.1:8000/user/api";
 private ServiceUser() {
        req = new ConnectionRequest() ; 
         }
    /* Singleton patron de conception de creation */
    public static ServiceUser getInstance() {
        if (instance == null)
        {
            instance = new ServiceUser();
        }
         return instance;
    }
    
     public ArrayList<User> parseEntitie(String jsonText){
        try {
            lists= new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String,Object> CategorieListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
           List< Map<String,Object>> list =(List< Map<String,Object>>) CategorieListJson.get("root");
           for ( Map<String,Object> obj: list){
             User c = new User();
             float id = Float.parseFloat(obj.get("id").toString());
             float cin = Float.parseFloat(obj.get("cin").toString());
             float soldepoint = Float.parseFloat(obj.get("soldepoint").toString());
               c.setCin((int)cin);
                c.setSoldepoint((int)soldepoint);
             c.setId((int)id);
             c.setNom(obj.get("nom").toString());
             c.setPrenom(obj.get("prenom").toString());
             
             c.setAdresse(obj.get("adresse").toString());
             c.setAdrmail(obj.get("adrmail").toString());
             c.setTel(obj.get("tel").toString());
             c.setType(obj.get("type").toString());
            c.setMdp(obj.get("mdp").toString());

lists.add(c);
         
        } }
           catch (IOException ex) {
                    Dialog.show("Alert", ex.getMessage(), new Command("OK"));
             
        }
          return lists;
 }
     
          public ArrayList<User> getAll(){
          String url = BASE_URL+"/usersApi";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                lists = parseEntitie(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return lists;
    }
           public boolean siginin (TextField email,TextField  password )
    { 
  
       String url = BASE_URL+"/signinMobile?email="+email.getText()+"&password="+password.getText();
       req.setUrl(url);
       req.addResponseListener(new ActionListener<NetworkEvent>(){ 
           @Override
            public void actionPerformed(NetworkEvent evt) {
                System.out.println(req.getResponseData());
                lists =parseEntitie(new String(req.getResponseData()));
                connectedUser = lists.get(0);
                System.out.println(connectedUser);
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
             }
    });
        System.out.println(""+resultOK);
       NetworkManager.getInstance().addToQueue(req);
        return resultOK;
    }
    public boolean update (int id,TextField tfNom,TextField tfPrenom,TextField tfAdrmail,TextField tfAdresse,TextField tfMdp,TextField tfTell,TextField tfCin )
    { 

       String url = BASE_URL+"/updateProfileMobile/"+id+"?nom="+tfNom.getText()+"&prenom="+tfPrenom.getText()+"&adrmail="+tfAdrmail.getText()+"&adresse="+tfAdresse.getText()+"&mdp="+tfMdp.getText()+"&tel="+tfTell.getText()+"&cin="+tfCin.getText();
       req.setUrl(url);
       req.addResponseListener(new ActionListener<NetworkEvent>(){ 
           @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
             }
    });
        System.out.println(""+resultOK);
       NetworkManager.getInstance().addToQueue(req);
        return resultOK;
    }
    
     public boolean signup (TextField tfNom,TextField tfPrenom,TextField tfAdrmail,TextField tfAdresse,TextField tfMdp,TextField tfTell,TextField tfCin )
    { 

       String url = BASE_URL+"/signUpMobile"+"?nom="+tfNom.getText()+"&prenom="+tfPrenom.getText()+"&adrmail="+tfAdrmail.getText()+"&adresse="+tfAdresse.getText()+"&mdp="+tfMdp.getText()+"&tel="+tfTell.getText()+"&cin="+tfCin.getText();
       req.setUrl(url);
       req.addResponseListener(new ActionListener<NetworkEvent>(){ 
           @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
             }
    });
        System.out.println(""+resultOK);
       NetworkManager.getInstance().addToQueue(req);
        return resultOK;
    }
     
             public void Supprimer(int id) {
        ConnectionRequest con = new ConnectionRequest();
        con.setUrl(BASE_URL+"/deleteUserMobile/"+id);
        con.setPost(false);
        con.addResponseListener((evt) -> {
            System.out.println(con.getResponseData());

        });
        NetworkManager.getInstance().addToQueueAndWait(con);

    }
                  public boolean addAdmin (TextField tfNom,TextField tfPrenom,TextField tfAdrmail,TextField tfAdresse,TextField tfMdp,TextField tfTell,TextField tfCin )
    { 

       String url = BASE_URL+"/addAdminMobile"+"?nom="+tfNom.getText()+"&prenom="+tfPrenom.getText()+"&adrmail="+tfAdrmail.getText()+"&adresse="+tfAdresse.getText()+"&mdp="+tfMdp.getText()+"&tel="+tfTell.getText()+"&cin="+tfCin.getText();
       req.setUrl(url);
       req.addResponseListener(new ActionListener<NetworkEvent>(){ 
           @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
             }
    });
        System.out.println(""+resultOK);
       NetworkManager.getInstance().addToQueue(req);
        return resultOK;
    }
}
