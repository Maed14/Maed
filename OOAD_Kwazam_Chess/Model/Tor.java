// Soukmaed Ong Yu Kang

// this a subclass for ChessPiece, and set movement logic for Tor piece

package Model;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class Tor extends ChessPiece {
    public Tor(String side, Position position, boolean movingUp) {
        super("Tor", side, position, movingUp);
    }
    @Override
    public Position[] toMoveDirection(Map<String, ChessPiece> pieces){
        List<Position> moveDirections = new ArrayList<>();

        Position currentPosition = getPosition();
        String side = getSide();


        int x = currentPosition.getX();
        int y = currentPosition.getY();

        List<Position> leftMove = new ArrayList<>();
        List<Position> rightMove = new ArrayList<>();
        List<Position> upMove = new ArrayList<>();
        List<Position> downMove = new ArrayList<>();

        // move left
        for (int i = x -1; i >= 0; i--) {
            Position nextPosition = new Position(i, y);
            if (validateIsOwnSide(nextPosition, pieces, side)) break;
            leftMove.add(nextPosition);
            if (validateIsEnemySide(nextPosition, pieces, side)) break;
        }

        // move right
        for (int i = x + 1; i < 5; i++) {
            Position nextPosition = new Position(i, y);
            if (validateIsOwnSide(nextPosition, pieces, side)) break;
            rightMove.add(nextPosition);
            if (validateIsEnemySide(nextPosition, pieces, side)) break;
        }

        // move up
        for (int i = y + 1; i < 8; i++) {
            Position nextPosition = new Position(x, i);
            if (validateIsOwnSide(nextPosition, pieces, side)) break;
            upMove.add(nextPosition);
            if (validateIsEnemySide(nextPosition, pieces, side)) break;
        }

        // move down
        for (int i = y - 1; i >= 0; i--) {
            Position nextPosition = new Position(x, i);
            if (validateIsOwnSide(nextPosition, pieces, side)) break;
            downMove.add(nextPosition);
            if (validateIsEnemySide(nextPosition, pieces, side)) break;
        }

        moveDirections.addAll(leftMove);
        moveDirections.addAll(rightMove);
        moveDirections.addAll(upMove);
        moveDirections.addAll(downMove);

        Position[] moveDirectionsArray = new Position[moveDirections.size()];
        moveDirectionsArray = moveDirections.toArray(moveDirectionsArray);
        

        return moveDirectionsArray;

    }


    

}