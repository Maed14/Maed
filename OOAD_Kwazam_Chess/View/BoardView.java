// Lai Joon Li

// This class is the view of the board game.

// This class is part of the View in the MVC design pattern.
// Explanation: The view is what the user sees and interacts with.
// It displays the game state and responds to user input.
package View;

import Model.*;
import javax.swing.*;
import java.awt.*;
import java.util.Map;

public class BoardView extends JFrame {
    private BoardModel model = new BoardModel();
    
    private JButton[][] squares = new JButton[model.getCols()][model.getRows()];
    private JPanel boardPanel;
    private JPanel topPanel;

    private JLabel timeLabel;
    private JLabel sideLabel;
    private JLabel roundLabel;

    private JButton musicBtn;
    private JButton soundBtn;
    private JButton saveBtn;
    private JButton quitBtn;

    private final Color LIGHT_SQUARE = new Color(235, 235, 208); // Light beige
    private final Color DARK_SQUARE = new Color(119, 148, 85); // Green
    private final Color PANEL_BACKGROUND = new Color(236, 236, 236); // Mac panel color
    private final Color BUTTON_BACKGROUND = new Color(246, 246, 246); // Mac button color
    private final Color BUTTON_BORDER = new Color(204, 204, 204); // Mac button border

    public BoardView() {
        setLayout(new BorderLayout());
        createTopPanel();
        createBoardPanel();

        add(topPanel, BorderLayout.NORTH);
        add(boardPanel, BorderLayout.CENTER);

        setTitle("Board Game");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        pack();
        setMinimumSize(new Dimension(500, getHeight()));
        setLocationRelativeTo(null);

        // Set window background
        getContentPane().setBackground(PANEL_BACKGROUND);
        setVisible(true);
    }

    private void createTopPanel() {
        topPanel = new JPanel();
        topPanel.setLayout(new BorderLayout());
        topPanel.setBorder(BorderFactory.createEmptyBorder(5, 10, 5, 10));
        topPanel.setBackground(PANEL_BACKGROUND);
    
        // Panel for buttons
        JPanel buttonPanel = new JPanel();
        buttonPanel.setLayout(new FlowLayout(FlowLayout.CENTER, 10, 5));
        buttonPanel.setBackground(PANEL_BACKGROUND);
    
        musicBtn = makeImgButton("music_open.png", 30, 30);
        soundBtn = makeImgButton("sound_open.png", 30, 30);
        saveBtn = createMacButton("Save");
        quitBtn = createMacButton("Quit");
    
        buttonPanel.add(musicBtn);
        buttonPanel.add(soundBtn);
        buttonPanel.add(saveBtn);
        buttonPanel.add(quitBtn);
    
        // Panel for labels
        JPanel labelPanel = new JPanel();
        labelPanel.setLayout(new FlowLayout(FlowLayout.CENTER, 10, 5));
        labelPanel.setBackground(PANEL_BACKGROUND);
    
        timeLabel = new JLabel("Time: 0:00");
        sideLabel = new JLabel("... Turn");
        roundLabel = new JLabel("Round: ...");
    
        labelPanel.add(timeLabel);
        labelPanel.add(sideLabel);
        labelPanel.add(roundLabel);
    
        // Add panels to topPanel
        topPanel.add(buttonPanel, BorderLayout.NORTH);
        topPanel.add(labelPanel, BorderLayout.SOUTH);
    
        Dimension minSize = new Dimension(500, 100);
        topPanel.setMinimumSize(minSize);
        topPanel.setPreferredSize(minSize);
    }
    

    private JButton createMacButton(String text) {
        JButton button = new JButton(text);
        button.setBackground(BUTTON_BACKGROUND);
        button.setBorder(BorderFactory.createCompoundBorder(
                BorderFactory.createLineBorder(BUTTON_BORDER, 1),
                BorderFactory.createEmptyBorder(4, 12, 4, 12)));
        button.setFocusPainted(false);
        button.setOpaque(true);
        return button;
    }

    private JButton makeImgButton(String imgName, int width, int height) {
        JButton btn = new JButton();
        try {
            setButtonImg(btn, imgName);
        } catch (Exception e) {
            btn.setText(imgName.replace(".png", ""));
        }
        btn.setPreferredSize(new Dimension(width, height));
        btn.setContentAreaFilled(false);
        btn.setBorderPainted(false);
        btn.setFocusPainted(false);
        return btn;
    }

    private void createBoardPanel() {
        boardPanel = new JPanel(new GridLayout(model.getCols(), model.getRows()));
        boardPanel.setBackground(PANEL_BACKGROUND);

        for (int i = 7; i >= 0; i--) {
            for (int j = 0; j < model.getRows(); j++) {
                squares[i][j] = new JButton();
                squares[i][j].setBorderPainted(false);
                squares[i][j].setPreferredSize(new Dimension(model.getSquareSize(), model.getSquareSize()));
                squares[i][j].setBackground((i + j) % 2 == 0 ? LIGHT_SQUARE : DARK_SQUARE);
                squares[i][j].setOpaque(true);
                boardPanel.add(squares[i][j]);
            }
        }
    }

    // Getters
    public JButton[][] getSquares() {
        return squares;
    }

    public JButton getSaveButton() {
        return saveBtn;
    }

    public JButton getQuitButton() {
        return quitBtn;
    }

    public JButton getMusicButton() {
        return musicBtn;
    }

    public JButton getSoundButton() {
        return soundBtn;
    }

    public void setCurrentSide(String side) {
        sideLabel.setText(side + "'s Turn");
        sideLabel.setForeground(side.equals("Red") ? Color.RED : Color.BLUE);
    }

    public void setRound(int round) {
        roundLabel.setText("Round: " + round);
    }

    public void setTime(int time) {
        int minit = 0;
        if (time >= 60) {
            minit = time / 60;
            time = time % 60;
        }
        timeLabel.setText("Time: " + minit + ":" + (time < 10 ? "0" : "") + time);
    }

    private void setButtonImg(JButton btn, String imgName) {
        try {
            ImageIcon icon = new ImageIcon("material/img/" + imgName);
            Image img = icon.getImage().getScaledInstance(20, 20, Image.SCALE_SMOOTH);
            btn.setIcon(new ImageIcon(img));
        } catch (Exception e) {
            btn.setText(imgName.replace(".png", ""));
        }
    }

    public void setMusicOn(boolean isMusicOn) {
        if (isMusicOn) {
            setButtonImg(musicBtn, "music_open.png");
        } else {
            setButtonImg(musicBtn, "music_close.png");
        }
    }

    public void setSoundOn(boolean isSoundOn) {
        if (isSoundOn) {
            setButtonImg(soundBtn, "sound_open.png");
        } else {
            setButtonImg(soundBtn, "sound_close.png");
        }
    }

    // Initialize the pieces on the board
    public void initializePieces(Map<String, ChessPiece> pieces) {
        for (int i = 0; i < squares.length; i++) {
            for (int j = 0; j < squares[i].length; j++) {
                squares[i][j].setIcon(null);
            }
        }

        pieces.forEach((pieceName, piece) -> {
            Position pos = piece.getPosition();
            squares[pos.getY()][pos.getX()].setIcon(piece.getPieceImage());
        });
    }

}