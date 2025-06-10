// Soukmaed Ong Yu Kang

// this a subclass for ChessPiece, and set movement logic for Xor piece

package Model;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class Xor extends ChessPiece {
    public Xor(String side, Position position, boolean movingUp) {  
        super("Xor", side, position, movingUp);
    }
    
    @Override
    public Position[] toMoveDirection(Map<String, ChessPiece> pieces){
        List<Position> moveDirections = new ArrayList<>();
        Position currentPosition = getPosition();
        int x = currentPosition.getX();
        int y = currentPosition.getY();
        // Top-right diagonal
        for (int i = 1; i < 8; i++) {
            Position nextPosition = new Position(x + i, y + i);
            if (!processPosition(nextPosition, pieces, moveDirections)) break;
        }
        // Top-left diagonal
        for (int i = 1; i < 8; i++) {
            Position nextPosition = new Position(x - i, y + i);
            if (!processPosition(nextPosition, pieces, moveDirections)) break;
        }
        // Bottom-right diagonal
        for (int i = 1; i < 8; i++) {
            Position nextPosition = new Position(x + i, y - i);
            if (!processPosition(nextPosition, pieces, moveDirections)) break;
        }
        // Bottom-left diagonal
        for (int i = 1; i < 8; i++) {
            Position nextPosition = new Position(x - i, y - i);
            if (!processPosition(nextPosition, pieces, moveDirections)) break;
        }

        return moveDirections.toArray(new Position[0]);
    }

    // Helper method to validate and process positions
    private boolean processPosition(Position position, Map<String, ChessPiece> pieces, List<Position> moves) {
        // Check if position is out of bounds
        if (position.getX() < 0 || position.getX() >= 5 || position.getY() < 0 || position.getY() >= 8) {
            return false;
        }

        // Stop processing if position is occupied by an own-side piece
        if (validateIsOwnSide(position, pieces, getSide())) {
            return false;
        }

        // Add position if it's valid
        moves.add(position);

        // Stop processing further if position
        if (validateIsEnemySide(position, pieces, getSide())) {
            return false; // Stop processing after adding the enemy piece position
        }

        return true;
    }
}
