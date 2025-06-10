// Soukmaed Ong Yu Kang

// this a subclass for ChessPiece, and set movement logic for Ram piece

package Model;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class Biz extends ChessPiece{
    public Biz(String side, Position position, boolean movingUp) {
        super("Biz", side, position, movingUp);
    }

    @Override
    public Position[] toMoveDirection(Map<String, ChessPiece> pieces) {
        Position currentPosition = getPosition();
        int x = currentPosition.getX();
        int y = currentPosition.getY();

        // move L shape
        Position[] possiblemove = new Position[]{
            new Position(x + 2, y + 1),
            new Position(x + 2, y - 1),
            new Position(x - 2, y + 1),
            new Position(x - 2, y - 1),
            new Position(x + 1, y + 2),
            new Position(x - 1, y + 2), 
            new Position(x + 1, y - 2),
            new Position(x - 1, y - 2)

        };

        List<Position> validmove = new ArrayList<>();

        for (Position nextPosition : possiblemove) {
            // Check if position is within board boundaries
            if (nextPosition.getX() < 0 || nextPosition.getX() >= 5 || nextPosition.getY() < 0 || nextPosition.getY() >= 8) {
                continue;
            }

            if (nextPosition.getY() >= 0 && nextPosition.getY() <= 7) {
                // Allow move if position is empty or occupied by an enemy piece
                if (!validateIsOwnSide(nextPosition, pieces, getSide())) {
                    validmove.add(nextPosition); // Add valid move
                }
            }
         }
        return validmove.toArray(new Position[0]);
    }
}
