// Eng Wei Jiun and Soukmaed Ong Yu Kang

// This class is the model class for the chess board.

// This class is part of the Model in the MVC design pattern.
// Explanation: The model is the core functional part of the program.
// They encapsulate the game's data and the logic of how the game is played.

package Model;

import java.util.*;

public class BoardModel {
    private Map<String, ChessPiece> pieces = new HashMap<>();
    private String side = "Blue";
    private int round = 0;

    private int rows = 5;
    private int cols = 8;
    private int squareSize = 60;

    private boolean isMusicOn = true;
    private boolean isSoundOn = true;

    private int redTime = 0;
    private int blueTime = 0;

    // Constructor to initialize the BoardModel with the pieces
    public BoardModel(){

        pieces.put("Red_Tor_1", new Tor("Red", new Position(0, 7), true));
        pieces.put("Red_Biz_1", new Biz("Red", new Position(1, 7), true));
        pieces.put("Red_Sau_1", new Sau("Red", new Position(2, 7), true));
        pieces.put("Red_Biz_2", new Biz("Red", new Position(3, 7), true));
        pieces.put("Red_Xor_1", new Xor("Red", new Position(4, 7), true));

        pieces.put("Red_Ram_1", new Ram("Red", new Position(0, 6), true));
        pieces.put("Red_Ram_2", new Ram("Red", new Position(1, 6), true));
        pieces.put("Red_Ram_3", new Ram("Red", new Position(2, 6), true));
        pieces.put("Red_Ram_4", new Ram("Red", new Position(3, 6), true));
        pieces.put("Red_Ram_5", new Ram("Red", new Position(4, 6), true));
        
        
        pieces.put("Blue_Ram_1", new Ram("Blue", new Position(0, 1), true));
        pieces.put("Blue_Ram_2", new Ram("Blue", new Position(1, 1), true));
        pieces.put("Blue_Ram_3", new Ram("Blue", new Position(2, 1), true));
        pieces.put("Blue_Ram_4", new Ram("Blue", new Position(3, 1), true));
        pieces.put("Blue_Ram_5", new Ram("Blue", new Position(4, 1), true));

        pieces.put("Blue_Xor_1", new Xor("Blue", new Position(0, 0), true));
        pieces.put("Blue_Biz_1", new Biz("Blue", new Position(1, 0), true));
        pieces.put("Blue_Sau_1", new Sau("Blue", new Position(2, 0), true));
        pieces.put("Blue_Biz_2", new Biz("Blue", new Position(3, 0), true));
        pieces.put("Blue_Tor_1", new Tor("Blue", new Position(4, 0), true));

    }

    // Constructor to initialize the BoardModel with the pieces, side and round
    public BoardModel(Map<String, ChessPiece> pieces, String side, int round, int blueTime, int redTime){
        this.pieces = pieces;
        this.side = side;
        int calculatedRound = (round * 2) - 2;

        if (side.equals("Red")) {
            calculatedRound++;
        }

        this.round = calculatedRound;
        this.blueTime = blueTime;
        this.redTime = redTime;

        if (side.equals("Red")) {
            pieces.forEach((pieceName, piece) -> {
                if (piece.getIsMovingUp()) piece.ratatePieceImage();
            });
        }
    }

    // Method to get the possible moves of a piece
    public Position[] pieceAction(String pieceName){
        return pieces.get(pieceName).toMoveDirection(pieces);
    }

    // Method to move a piece to a position
    public void movePiece(String movePieceName, Position position){

        String removePiece = getPieceNameByPosition(position);
        
        if (removePiece != null) {
            pieces.remove(removePiece);
        }

        pieces.get(movePieceName).setPosition(position);

    }

    // Method to flip the board
    public void flip(){
        for(ChessPiece piece : pieces.values()){
            piece.setPosition(new Position(4 - piece.getPosition().getX(), 7 - piece.getPosition().getY()));
            piece.ratatePieceImage();
        }
    }

    // Method to transform the Xor and Tor 
    public void transformsXorTor() {
        swapPositions("Blue_Tor_1", "Blue_Xor_1");
        swapPositions("Red_Tor_1", "Red_Xor_1");
    }

    // Method to swap the positions of the pieces
    private void swapPositions(String torKey, String xorKey) {
        Position torPosition = pieces.containsKey(torKey) ? pieces.get(torKey).getPosition() : null;
        Position xorPosition = pieces.containsKey(xorKey) ? pieces.get(xorKey).getPosition() : null;
    
        if (torPosition != null && xorPosition != null) {
            pieces.get(xorKey).setPosition(torPosition);
            pieces.get(torKey).setPosition(xorPosition);
        }
    }

    // Method for the Ram to turn around
    public void ramTurnsAround(Position pos) {
        ChessPiece piece = getPieceByPosition(pos);
        if (piece instanceof Ram) {
            if (piece.getPosition().getY() == 7 || piece.getPosition().getY() == 0) {
                piece.ratatePieceImage();
                piece.toggleMovingUp();
            }
        } 
    }

    // Method to check if the game is over
    public String gameOver() {
        if (!pieces.containsKey("Red_Sau_1")) {
            return "Blue";
        } else if (!pieces.containsKey("Blue_Sau_1")) {
            return "Red";
        }
        return null;
    }

    // Method to print the board info
    public String printBoardInfo() {
        String result = "";
        result = "Blue time:" + calculatedTime(blueTime) + "\n";
        result += "Red time:" + calculatedTime(redTime) + "\n";
        result += "CurrentTurn:" + side + "\n";
        result += "Round:" + Integer.toString(calculateRound()) + "\n";
        result += "\n";

        for (ChessPiece piece : pieces.values()) {
            if (piece.getSide().equals("Red")) {
                result += piece.getClass().getSimpleName() + "," + piece.getSide() + ",(" + piece.getPosition().getX() + "," + piece.getPosition().getY() + ")," + (piece.getIsMovingUp() ? "MoveUp" : "MoveDown") + "\n";
            }
        }

        result += "\n";

        for (ChessPiece piece : pieces.values()) {
            if (piece.getSide().equals("Blue")) {
                result += piece.getClass().getSimpleName() + "," + piece.getSide() + ",(" + piece.getPosition().getX() + "," + piece.getPosition().getY() + ")," + (piece.getIsMovingUp() ? "MoveUp" : "MoveDown") + "\n";
            }
        }
        return result;
    }

    // Method to calculate the round
    public int calculateRound() {
        int calculatedRound = (round % 2 == 0) ? round / 2 : (round - 1) / 2;
        calculatedRound++; // Increment the calculated round
        return calculatedRound;
    }

    // Method to calculate the time format(00:00)
    private String calculatedTime(int time) {
        int minit = 0;
        if(time >= 60){
            minit = time / 60;
            time = time % 60;
        }
        return minit + ":" + (time < 10 ? "0" : "") + time;
    }

    // Method to toggle the side
    public void toggleSide(){
        side = side.equals("Blue") ? "Red" : "Blue";
    }

    // Method to add a round
    public void addRound(){
        round++;
    }

    // Method to get the piece by position
    public ChessPiece getPieceByPosition(Position position){
        for(ChessPiece piece : pieces.values()){
            if(piece.getPosition().getX() == position.getX() && piece.getPosition().getY() == position.getY()){
                return piece;
            }
        }
        return null;
    }

    // Method to get the piece name by position
    private String getPieceNameByPosition(Position position){
        for(String pieceName : pieces.keySet()){
            if(pieces.get(pieceName).getPosition().getX() == position.getX() && pieces.get(pieceName).getPosition().getY() == position.getY()){
                return pieceName;
            }
        }
        return null;
    }

    // Method to get the pieces
    public Map<String, ChessPiece> getPieces() {
        return pieces;
    }

    // Method to get the side
    public String getSide(){
        return side;
    }

    // Method to get the round
    public int getRound(){
        return round;
    }
    
    // Method to get the rows
    public int getRows() {
        return rows;
    }

    // Method to get the cols
    public int getCols() {
        return cols;
    }

    // Method to get the squareSize
    public int getSquareSize() {
        return squareSize;
    }

    // Method to get the sound status
    public boolean getSoundOn() {
        return isSoundOn;
    }

    // Method to get the music status
    public boolean getMusicOn() {
        return isMusicOn;
    }

    // Method to toggle the sound status
    public void toggleSoundOn() {
        this.isSoundOn = !this.isSoundOn;
    }

    // Method to toggle the music status
    public void toggleMusicOn() {
        this.isMusicOn = !this.isMusicOn;
    }

    // Method to add red side time
    public void addRedTime() {
        redTime++;
    }

    // Method to add blue side time
    public void addBludTime() {
        blueTime++;
    }

    // Method to get red side time
    public int getRedTime() {
        return redTime;
    }

    // Method to get blue side time
    public int getBlueTime() {
        return blueTime;
    }

}
