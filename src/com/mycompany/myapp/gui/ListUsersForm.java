/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;


import com.codename1.messaging.Message;
import com.codename1.ui.Button;
import com.codename1.ui.ButtonGroup;
import com.codename1.ui.Command;
import com.codename1.ui.Component;
import static com.codename1.ui.Component.BOTTOM;
import static com.codename1.ui.Component.CENTER;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Graphics;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.RadioButton;
import com.codename1.ui.Tabs;
import com.codename1.ui.Toolbar;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.layouts.FlowLayout;
import com.codename1.ui.layouts.LayeredLayout;
import com.codename1.ui.util.Resources;

import com.mycompany.myapp.entities.User;
import java.util.ArrayList;
import java.io.IOException;

import com.mycompany.myapp.services.ServiceEvenement;
import com.mycompany.myapp.services.ServiceUser;

/**
 *
 * @author rihem
 */
public class ListUsersForm extends BaseFormBack {
     public ListUsersForm(Form previous,Resources res) throws IOException {
          super("user", BoxLayout.y());
        Toolbar tb = new Toolbar(true);
        setToolbar(tb);
        getTitleArea().setUIID("Container");
        setTitle("Expositions");
        getContentPane().setScrollVisible(false);
        
        super.addSideMenu(res);
       
                Tabs swipe = new Tabs();
                        Label spacer1 = new Label();
        Label spacer2 = new Label();

        addTab(swipe, spacer1);
          swipe.setUIID("Container");
        swipe.getContentPane().setUIID("Container");
        swipe.hideTabs();
        ButtonGroup bg = new ButtonGroup();
        int size = Display.getInstance().convertToPixels(1);
        Image unselectedWalkthru = Image.createImage(size, size, 0);
        Graphics g = unselectedWalkthru.getGraphics();
        g.setColor(0xffffff);
        g.setAlpha(100);
        g.setAntiAliased(true);
        g.fillArc(0, 0, size, size, 0, 360);
        Image selectedWalkthru = Image.createImage(size, size, 0);
        g = selectedWalkthru.getGraphics();
        g.setColor(0xffffff);
        g.setAntiAliased(true);
        g.fillArc(0, 0, size, size, 0, 360);
        RadioButton[] rbs = new RadioButton[swipe.getTabCount()];
        FlowLayout flow = new FlowLayout(CENTER);
        flow.setValign(BOTTOM);
        Container radioContainer = new Container(flow);
        for(int iter = 0 ; iter < rbs.length ; iter++) {
            rbs[iter] = RadioButton.createToggle(unselectedWalkthru, bg);
            rbs[iter].setPressedIcon(selectedWalkthru);
            rbs[iter].setUIID("Label");
            radioContainer.add(rbs[iter]);
        }
                
        rbs[0].setSelected(true);
        swipe.addSelectionListener((i, ii) -> {
            if(!rbs[ii].isSelected()) {
                rbs[ii].setSelected(true);
            }
        });
        
        Component.setSameSize(radioContainer, spacer1, spacer2);
        add(LayeredLayout.encloseIn(swipe, radioContainer));
        
          swipe.setUIID("Container");
        swipe.getContentPane().setUIID("Container");
        swipe.hideTabs();
              setTitle("List Users");
        setLayout(BoxLayout.yCenter());
          ArrayList<User> list;
        list = new ArrayList<>();
        list = ServiceUser.getInstance().getAll();

        for ( User c : list) {
            
              
                  Label i = new Label();
                   Label sp2 = new Label("ID: " + "  " + c.getId()); 
         sp2.getAllStyles().setFgColor(0x00000); 
                 
         Label spl = new Label("Nom: " + "  " + c.getNom()); 
         spl.getAllStyles().setFgColor(0x00000);
         Label spl2 = new Label("Prenom : " + "  " + c.getPrenom());
         spl2.getAllStyles().setFgColor(0x00000);
Label spl3 = new Label("Adresse : " + "  " + c.getAdresse());
         spl3.getAllStyles().setFgColor(0x00000);
Label spl4 = new Label("Email: " + "  " + c.getAdrmail());
         spl4.getAllStyles().setFgColor(0x00000);
Label spl5 = new Label("Tell : " + "  " + c.getTel());
         spl5.getAllStyles().setFgColor(0x00000);
         Label spl6 = new Label("Cin : " + "  " + c.getCin());
         spl6.getAllStyles().setFgColor(0x00000);
         
         
         Button sup = new Button("Delete");
                  Button upd = new Button("Update");

             
                sup.addActionListener((evt) -> {
                                        Dialog.show("Alert", "Delete User ?", new Command("OK"));

                   ServiceUser.getInstance().Supprimer(c.getId());
                    Dialog.show("Success", "User deleted successfully", new Command("OK"));
                    });
                
        addAll(sp2,spl,spl2,spl3,spl4,spl5,spl6,sup)
                ;}
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e-> previous.showBack());
    }
    
    private void addTab(Tabs swipe,  Label spacer) {
        int size = Math.min(Display.getInstance().getDisplayWidth(), Display.getInstance().getDisplayHeight());
        
        Label overlay = new Label(" ", "ImageOverlay");
        
        Container page1 = 
            LayeredLayout.encloseIn(
               
                overlay,
                BorderLayout.south(
                    BoxLayout.encloseY(
                           
                            spacer
                        )
                )
            );

        swipe.addTab("", page1);
    }
}
