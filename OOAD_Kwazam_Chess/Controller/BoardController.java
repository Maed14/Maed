// Eng Wei Jiun

// This class is the controller class for the chess board.

// This class is part of the Controller in the MVC design pattern.
// Explanation: The controllers process input and update the model.
// And then updates the Model based on the player's actions.

package Controller;

import Model.*;
import View.*;

import javax.swing.*;
import java.awt.*;
import java.util.TimerTask;
import java.util.Timer;
import java.io.*;

public class BoardController {
    private BoardView view;
    private BoardModel model;

    private MusicListener musicListener;
    private SoundListener soundListener;

    private String currentChooicePiece;

    private boolean isFlipDone = false;
    private boolean isGameOver = false;

    private Timer timer;

    // Constructor to initialize the BoardController for a new game
    public BoardController(BoardView view, BoardModel model) {
        this.view = view;
        this.model = model;

        timer = new Timer();

        musicListener = new MusicListener();
        soundListener = new SoundListener();

        view.setCurrentSide(model.getSide());
        view.setRound(model.calculateRound());

        view.initializePieces(model.getPieces());

        initializeAction();

        initializeButton();

        timerStart();
    }

    // initialize the button action
    private void initializeButton() {
        view.getSaveButton().addActionListener(e -> {
            if (isGameOver) {
                JOptionPane.showMessageDialog(null, "Game is over, you can't save the game", "Save Game", JOptionPane.INFORMATION_MESSAGE);
                return;
            }
            try {
                timerStop();
                new File("record.txt");
                FileWriter myWriter = new FileWriter("record.txt");

                myWriter.write(model.printBoardInfo());
                myWriter.close();

                int resultSave = JOptionPane.showConfirmDialog(null, "Game saved successfully", "Save Game", JOptionPane.DEFAULT_OPTION, JOptionPane.INFORMATION_MESSAGE);

                if (resultSave == JOptionPane.OK_OPTION) {
                    // Only execute this if "OK" is clicked
                    musicListener.stopMusic();
                    view.setVisible(false);
                    view.dispose();

                    new MenuController(new MenuView(), new MenuModel());
                    return;
                }
                timerStart();
                
              } catch (IOException error) {
                System.out.println("An error occurred.");
                error.printStackTrace();
              }
        });

        view.getQuitButton().addActionListener(e -> {
            timerStop();
            int resultQuit = JOptionPane.showConfirmDialog(null, "Game quit successfully", "Quit Game", JOptionPane.DEFAULT_OPTION, JOptionPane.INFORMATION_MESSAGE);

            if (resultQuit == JOptionPane.OK_OPTION) {
                // Only execute if "OK" is clicked
                musicListener.stopMusic();
                view.setVisible(false);
                view.dispose();

                new MenuController(new MenuView(), new MenuModel());
                return;
            } 

            timerStart();
        });

        view.getMusicButton().addActionListener(e -> {
            model.toggleMusicOn();
            view.setMusicOn(model.getMusicOn());
            playMusic();
        });

        view.getSoundButton().addActionListener(e -> {
            model.toggleSoundOn();
            view.setSoundOn(model.getSoundOn());
        });
    }

    // Initialize the action for each square on the board
    private void initializeAction() {
        for (int i = 0; i < model.getCols(); i++) {
            for (int j = 0; j < model.getRows(); j++) {

                JButton square = view.getSquares()[i][j];
                Position pos = new Position(j, i);
    
                square.addActionListener(e -> {
                    ChessPiece piece = model.getPieceByPosition(pos); 
    
                    if (piece != null && piece.getSide().equals(model.getSide()) && !isFlipDone && !isGameOver) { 
                        pieceAction(square);
                    } else {
                        MoveAction(square, pos);
                    }
                });
            }
        }
    }
    
    // action for piece
    private void pieceAction(JButton square) {
        model.getPieces().forEach((pieceName, piece) -> {
            if (square.getIcon().equals(piece.getPieceImage())) {
                clearHighlightedSquares();
                
                currentChooicePiece = pieceName;
                Position[] moveDirection = model.pieceAction(pieceName);
                
                for (Position direction : moveDirection) {
                    view.getSquares()[direction.getY()][direction.getX()].setBackground(new Color(255, 0, 0));
                }
                return;
            }
        });
    }

    // action for move
    private void MoveAction(JButton square, Position pos) {
        
            if (square.getBackground().equals(new Color(255, 0, 0)) && currentChooicePiece != null) { 

                timerStop();

                isFlipDone = true;

                // play sound effect
                playSoundEffect();

                // move the piece
                model.movePiece(currentChooicePiece, pos);

                currentChooicePiece = null;

                // check if the ram move at edge and turns around
                model.ramTurnsAround(pos);

                // add round
                model.addRound();

                // update the round
                view.setRound(model.calculateRound());

                // if each 2 round, transform xor and tor
                if (model.getRound() % 4 == 0) {
                    model.transformsXorTor();
                }

                // clear highlighted squares
                clearHighlightedSquares();
                
                view.initializePieces(model.getPieces());

                // delay 0.5 saat for flip side
                Timer timer = new Timer();
                timer.schedule(new TimerTask() {
                    @Override
                    public void run() {
                        
                        // check if the game is over
                        String gameOverMessage = model.gameOver();
                        if (gameOverMessage != null) {
                            timerStop();

                            JOptionPane.showMessageDialog(null, gameOverMessage + "winner!!", "Game Over", JOptionPane.INFORMATION_MESSAGE);
                            isGameOver = true;
                            timerStop();
                        } else {
                                // change side
                            changeSide();

                            // flip the board
                            model.flip();

                            isFlipDone = false;

                            view.initializePieces(model.getPieces());

                            timerStart();
                        }
                    }
                }, 500);

            }
        
    }

    // clear the highlighted squares
    private void clearHighlightedSquares() {
        for (int i = 0; i < model.getCols(); i++) {
            for (int j = 0; j < model.getRows(); j++) {
                if ((i + j) % 2 == 0) {
                    view.getSquares()[i][j].setBackground(new Color(235, 235, 208));
                } else {
                    view.getSquares()[i][j].setBackground(new Color(119, 148, 85));
                }
            }
        }
    }

    // change the side
    private void changeSide() {
        model.toggleSide();
        view.setCurrentSide(model.getSide());
    }

    // play sound effect
    private void playSoundEffect() {
        if (model.getSoundOn()) {
            soundListener.playSoundEffect();
        }
    }

    // play music
    private void playMusic() {
        if (model.getMusicOn()) {
            musicListener.playMusic();
        } else {
            musicListener.stopMusic();
        }
    }

    // stop the timer
    private void timerStop() {
        timer.cancel();
    }

    // start the timer
    private void timerStart() {
        timer = new Timer();
        timer.scheduleAtFixedRate(new TimerTask() {
            @Override
            public void run() {

                if(model.getSide().equals("Red")){
                    // set the view to red side time
                    model.addRedTime();
                    view.setTime(model.getRedTime());
                } else {
                    // set the view to blue side time
                    model.addBludTime();
                    view.setTime(model.getBlueTime());
                }
            }
        }, 0, 1000);
    }

}
