// This is the entry point of the Kwazam Chess game.
// We have implemented Model-View-Controller (MVC) 
// and Strategy Pattern as our Design Pattern.

import Controller.*;
import Model.*;
import View.*;

public class Main {

    public static void main(String[] args) {

        MenuView view = new MenuView();
        MenuModel model = new MenuModel();
        new MenuController(view, model);

    }
}