// Eng Wei Jiun

// Main controller for the menu view.

// This class is part of the Controller in the MVC design pattern.
// Explanation: The controllers process input and update the model.
// And then updates the Model based on the player's actions.

package Controller;

import View.*;
import Model.*;

import java.awt.event.*;
import java.io.*;
import java.util.*;
import javax.swing.*;

public class MenuController {

    private MenuView view;
    private MenuModel model;

    private MusicListener musicListener;
    private SoundListener soundListener;

    private JButton musicButton;
    private JButton soundButton;
    private JButton newGameButton;
    private JButton loadGameButton;

    public MenuController(MenuView view, MenuModel model) {
        this.view = view;
        this.model = model;

        musicListener = new MusicListener();
        soundListener = new SoundListener();

        addMenuListener();
    }

    // Add action listeners for the menu buttons
    private void addMenuListener() {

        // Add action listener for the music button
        musicButton = view.getMusicButton();
        musicButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                playSoundEffect();
                model.toggleMusic();
                view.setMusicOn(model.getMusicOn());
                playMusic();
            }
        });
        
        // Add action listener for the sound effects button
        soundButton = view.getSoundEffectsButton();
        soundButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                model.toggleSoundEffects();
                view.setSoundEffectsOn(model.getSoundEffectsOn());
                playSoundEffect();
            }
        });

        // Add action listener for the new game button
        newGameButton = view.getStartButton();
        newGameButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                playSoundEffect();

                musicListener.stopMusic();

                view.setVisible(false);
                view.dispose();

                BoardView view = new BoardView();
                BoardModel model = new BoardModel();
                new BoardController(view, model);
            }
        });

        // Add action listener for the load game button
        loadGameButton = view.getLoadGameButton();
        loadGameButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                playSoundEffect();
                musicListener.stopMusic();

                view.setVisible(false);
                view.dispose();

                loadGame();
            }
        });
    }

    // Play sound effect if sound effects are enabled
    private void playSoundEffect() {
        if (model.getSoundEffectsOn()) {
            soundListener.playSoundEffect();
        }
    }

    // Play music if music is enabled
    private void playMusic() {
        if (model.getMusicOn()) {
            musicListener.playMusic();
        } else {
            musicListener.stopMusic();
        }
    }

    // Load the game from the record file
    private void loadGame() {
        int blueTime = 0;
        int redTime = 0;
        String currentTurn = null;
        int round = 0;
        Map<String, ChessPiece> pieces = new HashMap<>();

        int iTorRed = 0;
        int iBizRed = 0;
        int iSauRed = 0;
        int iXorRed = 0;
        int iRamRed = 0;

        int iTorBlue = 0;
        int iBizBlue = 0;
        int iSauBlue = 0;
        int iXorBlue = 0;
        int iRamBlue = 0;

        // if the record file does not exist, return
        if (!new File("record.txt").exists()) {
            JOptionPane.showMessageDialog(null, "No saved game found", "Load Game", JOptionPane.INFORMATION_MESSAGE);
            return;
        }
        
        try {
            File file = new File("record.txt");
            Scanner readFile = new Scanner(file);

            while (readFile.hasNextLine()) {
                String data = readFile.nextLine();

                if (data.isEmpty()) continue; 

                // get blue time
                if (data.startsWith("Blue time:")) {
                    int minit = Integer.parseInt(data.split(":")[1].trim());
                    int sec = Integer.parseInt(data.split(":")[2].trim());
                    blueTime = minit * 60 + sec;
                }

                // get red time
                if (data.startsWith("Red time:")) {
                    int minit = Integer.parseInt(data.split(":")[1].trim());
                    int sec = Integer.parseInt(data.split(":")[2].trim());
                    redTime = minit * 60 + sec;
                }

                // get current turn
                if (data.startsWith("CurrentTurn:")) {
                    currentTurn = data.split(":")[1].trim();
                }

                // get round
                if (data.startsWith("Round:")) {
                    round = Integer.parseInt(data.split(":")[1].trim());
                }

                // get pieces
                String[] parts = data.split(",");
                if(parts.length == 5) {
                    String pieceName = parts[0].trim();
                    String side = parts[1].trim();
                    String x = parts[2].trim().replace("(", "");
                    String y = parts[3].trim().replace(")", "");
                    boolean movingUp = parts[4].trim().equals("MoveDown") ? false : true;

                    String key;
                    switch (pieceName) {
                        case "Biz":
                            key = getKeyName(side, pieceName, side.equals("Red") ? ++iBizRed : ++iBizBlue);
                            pieces.put(key, new Biz(side, new Position(Integer.parseInt(x), Integer.parseInt(y)), movingUp));
                            break;
                        case "Tor":
                            key = getKeyName(side, pieceName, side.equals("Red") ? ++iTorRed : ++iTorBlue);
                            pieces.put(key, new Tor(side, new Position(Integer.parseInt(x), Integer.parseInt(y)), movingUp));
                            break;
                        case "Sau":
                            key = getKeyName(side, pieceName, side.equals("Red") ? ++iSauRed : ++iSauBlue);
                            pieces.put(key, new Sau(side, new Position(Integer.parseInt(x), Integer.parseInt(y)), movingUp));
                            break;
                        case "Xor":
                            key = getKeyName(side, pieceName, side.equals("Red") ? ++iXorRed : ++iXorBlue);
                            pieces.put(key, new Xor(side, new Position(Integer.parseInt(x), Integer.parseInt(y)), movingUp));
                            break;
                        case "Ram":
                            key = getKeyName(side, pieceName, side.equals("Red") ? ++iRamRed : ++iRamBlue);
                            pieces.put(key, new Ram(side, new Position(Integer.parseInt(x), Integer.parseInt(y)), movingUp));
                            break;
                        default:
                            System.out.println("Unknown piece type: " + pieceName);
                    }
                    
                }
            }
            readFile.close();

            // Start the game
            new BoardController(new BoardView(), new BoardModel(pieces, currentTurn, round, blueTime, redTime));

        } catch (FileNotFoundException e) {
            System.out.println("An error occurred.");
            e.printStackTrace();
        }

    }

    // Generate a key name for the piece
    private static String getKeyName(String side, String pieceName, int counter) {
        return side + "_" + pieceName + "_" + counter;
    }

}