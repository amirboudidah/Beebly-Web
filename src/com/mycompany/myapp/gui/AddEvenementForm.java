/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.components.ScaleImageLabel;
import com.codename1.ui.Button;
import com.codename1.ui.ButtonGroup;
import com.codename1.ui.ComboBox;
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
import com.codename1.ui.TextField;
import com.codename1.ui.Toolbar;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.layouts.FlowLayout;
import com.codename1.ui.layouts.LayeredLayout;
import com.codename1.ui.plaf.Style;
import com.codename1.ui.spinner.Picker;
import com.codename1.ui.util.Resources;
import com.mycompany.myapp.entities.User;

import com.mycompany.myapp.services.ServiceEvenement;
import com.mycompany.myapp.services.ServiceUser;
import java.util.ArrayList;
import java.util.Vector;
/**
 *
 * @author azizk
 */
public class AddEvenementForm extends BaseFormBack {
    
     public AddEvenementForm(Form previous,Resources res) {
         super("evenement", BoxLayout.y());
        Toolbar tb = new Toolbar(true);
        setToolbar(tb);
        getTitleArea().setUIID("Container");
        setTitle("Add evenement");
        getContentPane().setScrollVisible(false);
        
        super.addSideMenu(res);
        //tb.addSearchCommand(e -> {});
                Tabs swipe = new Tabs();
                        Label spacer1 = new Label();
        Label spacer2 = new Label();

        addTab(swipe,  spacer1);
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
              setTitle("Add evenement");
        setLayout(BoxLayout.yCenter());
        
    
        
        TextField tfLibelle = new TextField(""," Libelle ");
        TextField tfDescription = new TextField("","Description");
                
        TextField tfEmplacement = new TextField(""," Emplacement");
        
        
        TextField tfDuree = new TextField(""," Duree");
        TextField tfPlaces = new TextField("","NB places");
          
Vector<String> vectorCommande = new Vector<>();
ArrayList<User> enn = ServiceUser.getInstance().getAll();
for (User user : enn) {
    vectorCommande.add(user.getNom());
}

ComboBox<String> users = new ComboBox<>(vectorCommande);
users.setUIID("ComboBox");

        Button btnValider = new Button("Add evenement");
     
        btnValider.addActionListener(new ActionListener() {
             @Override
             public void actionPerformed(ActionEvent evt) {
                 if ((tfLibelle.getText().length()==0)||(tfDescription.getText().length()==0) ||
                         (tfEmplacement.getText().length()==0)||(tfDuree.getText().length()==0)||
                        (tfPlaces.getText().length()==0) )
                    Dialog.show("Alert", "Please fill all the fields", new Command("OK"));
                 else {
                    try {
                     
int idUser = enn.get(users.getSelectedIndex()).getId();
                        if(ServiceEvenement.getInstance().add(tfLibelle, tfDescription, tfPlaces, tfEmplacement, tfDuree, idUser))
                            Dialog.show("Success","Connection accepted",new Command("OK"));
                 
                    } catch (NumberFormatException e) {
                        Dialog.show("ERROR", "price must be a number", new Command("OK"));
                    
                   }
  Dialog.show("Success","Connection accepted",new Command("OK"));
                 }}
        });
      addAll(tfLibelle,tfDescription,tfPlaces,tfDuree,tfEmplacement);
                        addStringValue("User", users); 
         addAll(btnValider);
      getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e-> previous.showBack());
    
    }
    private void addTab(Tabs swipe, Label spacer) {
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
          private void addStringValue(String s, Component v) {
        add(BorderLayout.west(new Label(s, "PaddedLabel")).
                add(BorderLayout.CENTER, v));
        add(createLineSeparator(0xeeeeee));
    }
}
