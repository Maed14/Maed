// Soukmaed Ong Yu Kang and Eng Wei Jiun
 
// this class is parent class for all chess pieces

// This class is part of the Model in the MVC design pattern.
// Explanation: The model is the core functional part of the program.
// They encapsulate the game's data and the logic of how the game is played.

// Implementation of Strategy Pattern:
// Explaination: We separate the movement logic of each piece into its own subclass (Ram, Tor, Xor, Biz, Sau).
// This approach ensures that each chess piece is responsible for its own behavior 
// while allowing the game to operate smoothly.

package Model;

import javax.swing.*;
import java.awt.*;
import java.util.*;
import java.awt.geom.AffineTransform;
import java.awt.image.BufferedImage;

public abstract class ChessPiece {
    private Position position;
    private String side;
    private ImageIcon pieceImage;
    protected boolean movingUp = true;

    // set the piece name, side, position and moving direction
    public ChessPiece(String pieceName, String side, Position position, boolean movingUp) { 
        this.side = side;
        this.pieceImage = setPieceImage(pieceName);
        this.position = position;
        this.movingUp = movingUp;
    }

    // rotate the image of the piece
    public void ratatePieceImage(){
        this.pieceImage = rotateIcon(this.pieceImage, 180);
    }

    // abstract method to move the piece
    public abstract Position[] toMoveDirection(Map<String, ChessPiece> pieces);

    // validate if the position is occupied by an own-side piece
    protected boolean validateIsOwnSide(Position position, Map<String, ChessPiece> pieces, String side) {
        for(ChessPiece piece : pieces.values()){
            if(piece.getSide().equals(side) && piece.getPosition().getX() == position.getX() && piece.getPosition().getY() == position.getY()){
                return true;
            }
        }
        return false;
    }
    protected boolean validateIsEnemySide(Position position, Map<String, ChessPiece> pieces, String side) {
        for(ChessPiece piece : pieces.values()){
            if(piece.getSide() != side && piece.getPosition().getX() == position.getX() && piece.getPosition().getY() == position.getY()){
                return true;
            }
        }
        return false;
    }


    // toggle the moving direction of the piece
    public void toggleMovingUp() {
        movingUp = !movingUp;
    }

    // get the image of the piece
    public ImageIcon getPieceImage() {
        return this.pieceImage;
    }

    // get the position of the piece
    public Position getPosition() {
        return this.position;
    }

    // set the position of the piece
    public void setPosition(Position position) {
        this.position = position;
    }

    // get the side of the piece
    public String getSide() {
        return this.side;
    }

    // get the moving direction of the piece
    public boolean getIsMovingUp() {
        return movingUp;
    }
    
    // rotate the image of the piece
    private ImageIcon rotateIcon(ImageIcon icon, double angle) {
        // Convert to BufferedImage
        Image image = icon.getImage();
        BufferedImage bufferedImage = new BufferedImage(image.getWidth(null), image.getHeight(null), BufferedImage.TYPE_INT_ARGB);

        // Calculate the new size of the image based on the angle of rotaion
        double radians = Math.toRadians(angle);
        double sin = Math.abs(Math.sin(radians));
        double cos = Math.abs(Math.cos(radians));
        int newWidth = (int) Math.round(bufferedImage.getWidth() * cos + bufferedImage.getHeight() * sin);
        int newHeight = (int) Math.round(bufferedImage.getWidth() * sin + bufferedImage.getHeight() * cos);

        // Create a new image
        BufferedImage rotate = new BufferedImage(newWidth, newHeight, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g2d = rotate.createGraphics();

        // Calculate the "anchor" point around which the image will be rotated
        int x = (newWidth - bufferedImage.getWidth()) / 2;
        int y = (newHeight - bufferedImage.getHeight()) / 2;

        // Transform the origin point around the anchor point
        AffineTransform at = new AffineTransform();
        at.setToRotation(radians, x + (bufferedImage.getWidth() / 2), y + (bufferedImage.getHeight() / 2));
        at.translate(x, y);
        g2d.setTransform(at);

        // Paint the originl image
        g2d.drawImage(image, 0, 0, null);
        g2d.dispose();

        return new ImageIcon(rotate);
    }

    // set the image of the piece
    private ImageIcon setPieceImage(String pieceName) {
        String path = "/material/img/" + side + "_"  + pieceName + ".png";
        Image image = new ImageIcon(this.getClass().getResource(path)).getImage();
        if(image == null){
            System.out.println("Image not found" + path);
            return null;
        }
        Image scaledImage = image.getScaledInstance(50, 50, Image.SCALE_SMOOTH);
        return new ImageIcon(scaledImage);
    }
    
}
