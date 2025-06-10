// Lai Joon Li

// This class is responsible for displaying the main menu of the game.

// This class is part of the View in the MVC design pattern.
// Explanation: The view is what the user sees and interacts with.
// It displays the game state and responds to user input.
package View;

import javax.swing.*;
import java.awt.*;
import java.awt.image.*;
import java.io.*;
import javax.imageio.*;

public class MenuView extends JFrame {
    private JButton startButton;
    private JButton loadGameButton;
    private JButton soundEffectsButton;
    private JButton musicButton;
    JPanel panel;

    private BufferedImage backgroundImg;
    private boolean isSoundEffectsOn = true;
    private boolean isMusicOn = true;

    public MenuView() {
        try {
            backgroundImg = ImageIO.read(new File("material/img/menu_background.png"));
        } catch (IOException e) {
            System.err.println("Error loading background image: " + e.getMessage());
        }

        this.setTitle("Main Menu");
        this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        this.setPreferredSize(new Dimension(500, 600)); // Set preferred size
        this.setMinimumSize(new Dimension(500, 600)); // Set minimum size
        this.setLayout(new BorderLayout());

        // Initialize the panel with null layout
        panel = new JPanel() {
            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                if (backgroundImg != null) {
                    g.drawImage(backgroundImg, 0, 0, getWidth(), getHeight(), null);
                }
            }
        };
        panel.setLayout(null);
        panel.setPreferredSize(new Dimension(500, 600)); // Set panel size

        // Create buttons
        int buttonWidth = 165; // About 33% of 500
        int buttonHeight = 72; // About 12% of 600

        startButton = createCustomButton("material/img/Start.png", buttonWidth, buttonHeight);
        loadGameButton = createCustomButton("material/img/LoadGame.png", buttonWidth, buttonHeight);
        soundEffectsButton = createCustomButton("material/img/SoundEffect.png", buttonWidth, buttonHeight);
        musicButton = createCustomButton("material/img/Music.png", buttonWidth, buttonHeight);

        panel.add(startButton);
        panel.add(loadGameButton);
        panel.add(soundEffectsButton);
        panel.add(musicButton);

        this.add(panel);

        // Add resize listener
        this.addComponentListener(new java.awt.event.ComponentAdapter() {
            @Override
            public void componentResized(java.awt.event.ComponentEvent e) {
                repositionButtons(panel);
                resizeButtons();
            }
        });

        repositionButtons(panel);
        pack();
        setLocationRelativeTo(null);
        this.setVisible(true);
    }

    private void resizeButtons() {
        int buttonWidth = (int) (getWidth() * 0.33);
        int buttonHeight = (int) (getHeight() * 0.12);

        resizeButtonIcon(startButton, "material/img/Start.png", buttonWidth, buttonHeight);
        resizeButtonIcon(loadGameButton, "material/img/LoadGame.png", buttonWidth, buttonHeight);

        String soundEffectsPath = isSoundEffectsOn ? "material/img/SoundEffect.png"
                : "material/img/CancelSoundEffect.png";
        String musicPath = isMusicOn ? "material/img/Music.png" : "material/img/CancelMusic.png";

        resizeButtonIcon(soundEffectsButton, soundEffectsPath, buttonWidth, buttonHeight);
        resizeButtonIcon(musicButton, musicPath, buttonWidth, buttonHeight);
    }

    private void repositionButtons(JPanel panel) {
        int panelWidth = panel.getWidth();
        int panelHeight = panel.getHeight();

        int buttonWidth = (int) (panelWidth * 0.33);
        int buttonHeight = (int) (panelHeight * 0.12);
        int gap = (int) (panelHeight * 0.01);

        int totalHeight = 3 * buttonHeight + 3 * gap;
        int startY = (panelHeight - totalHeight) / 2;
        int centerX = (panelWidth - buttonWidth) / 2;

        startButton.setBounds(centerX, startY, buttonWidth, buttonHeight);
        loadGameButton.setBounds(centerX, startY + buttonHeight + gap, buttonWidth, buttonHeight);
        soundEffectsButton.setBounds(centerX, startY + 2 * (buttonHeight + gap), buttonWidth, buttonHeight);
        musicButton.setBounds(centerX, startY + 3 * (buttonHeight + gap), buttonWidth, buttonHeight);

        panel.repaint();
    }

    private void resizeButtonIcon(JButton button, String imagePath, int width, int height) {
        try {
            ImageIcon originalIcon = new ImageIcon(imagePath);
            Image scaledImage = originalIcon.getImage().getScaledInstance(width, height, Image.SCALE_SMOOTH);
            button.setIcon(new ImageIcon(scaledImage));
            button.setPreferredSize(new Dimension(width, height));
        } catch (Exception e) {
            System.err.println("Error resizing button image: " + imagePath + " - " + e.getMessage());
        }
    }

    private JButton createCustomButton(String imagePath, int width, int height) {
        JButton button = new JButton();
        try {
            ImageIcon icon = new ImageIcon(imagePath);
            Image scaledImage = icon.getImage().getScaledInstance(width, height, Image.SCALE_SMOOTH);
            button.setIcon(new ImageIcon(scaledImage));
            button.setPreferredSize(new Dimension(width, height));
        } catch (Exception e) {
            System.err.println("Error loading button image: " + imagePath + " - " + e.getMessage());
        }
        button.setContentAreaFilled(false);
        button.setBorderPainted(false);
        button.setFocusPainted(false);
        return button;
    }

    // Button getters
    public JButton getStartButton() {
        return startButton;
    }

    public JButton getLoadGameButton() {
        return loadGameButton;
    }

    public JButton getSoundEffectsButton() {
        return soundEffectsButton;
    }

    public JButton getMusicButton() {
        return musicButton;
    }

    public void setSoundEffectsOn(boolean isSoundEffectsOn) {
        this.isSoundEffectsOn = isSoundEffectsOn;
        resizeButtons();
    }

    public void setMusicOn(boolean isMusicOn) {
        this.isMusicOn = isMusicOn;
        resizeButtons();
    }

}