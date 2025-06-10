// Soukmaed Ong Yu Kang

// this a subclass for ChessPiece, and set movement logic for Ram piece

package Model;

import java.util.*;

public class Ram extends ChessPiece {
    // private boolean movingUp = true;
    public Ram(String side, Position position, boolean movingUp) {
        super("Ram", side, position, movingUp);
    }
    
    @Override
    public Position[] toMoveDirection(Map<String, ChessPiece> pieces) {
        List<Position> validmove = new ArrayList<>();
        Position currentPosition = getPosition();
        int x = currentPosition.getX();
        int y = currentPosition.getY();

        Position nextPosition;
        if (movingUp) {
            nextPosition = new Position(x, y + 1); // moving upward
            if (y + 1 > 7) {
                nextPosition = new Position(x, y - 1); // the piece moving downward
            }
        } else {
            nextPosition = new Position(x, y - 1);
            if (y - 1 < 0) {
                nextPosition = new Position(x, y + 1); // so the piece moving upward
            }
        }

        if (nextPosition.getY() >= 0 && nextPosition.getY() <= 7) {
            // Allow move if position is empty or occupied by an enemy piece
            if (!validateIsOwnSide(nextPosition, pieces, getSide())) {
                validmove.add(nextPosition); // Add valid move
            }
        }
        return validmove.toArray(new Position[0]);
    }
}